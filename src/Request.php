<?php
/**
 * MobileBill - 2Bill.ie PHP Api Client
 *
 * @author    Ciarán Maher <ciaran@square1.io>
 * @copyright 2014 Square1 Software Ltd. http://square1.io
 * @link      http://bit.ly/1fTu6zb
 * @package   MobileBill
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Square1\MobileBill;

use Guzzle\Http\Client;
use Square1\MobileBill\Exception\AuthenticationError;
use Square1\MobileBill\Exception\ConnectionError;
use Square1\MobileBill\Exception\InvalidNumber;
use Square1\MobileBill\Exception\InvalidRequest;
use Square1\MobileBill\Exception\TransactionError;

/**
 * Request
 *
 * This class handles the building of the XML request as well as processing the XMl
 * responce from the API.
 *
 * @package MobileBill
 * @author  Ciaran Maher
 * @since   1.0.0
 */
class Request
{
    /**
     * The request data array.
     * Pre-loaded with a number of request default values.
     *
     * @type array request
     */
    private $data = array();

    /**
     * A well-formed XML string.
     *
     * @type string xml
     */
    private $xml;

    /**
     * The URL endpoint.
     *
     * @type string url
     */
    private $url;

    /**
     * The Guzzle object
     *
     * @type \Guzzle\Http\Client
     */
    private $client;

    /**
     * Request Construtor
     *
     * @param \Guzzle\Http\Client $client The Guzzle client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Set the request data array
     * 
     * @param string $key   The array key
     * @param string $value The array value
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Set the XML string
     * 
     * @param string $xmlString Valid XML string
     */
    public function setXml($xmlString)
    {
        $this->xml = $xmlString;
    }

    /**
     * Set the URL endpoint
     * 
     * @param string $urlString The URL endpoint
     */
    public function setUrl($urlString)
    {
        $this->url = $urlString;
    }

    /**
     * Genereated and return the XML string
     * 
     * @return string A valid XML string
     */
    private function getXml()
    {
        $xml = new \SimpleXMLElement($this->xml);

        foreach ($this->get() as $key => $value) {
            $xml->addChild($key, $value);
        }
        return $xml->asXML();
    }

    /**
     * Get the request data array
     * 
     * @return array The request array
     */
    private function get()
    {
        //Update datestamp each time we return the request data array
        $this->set('DATEREQUEST', date('Y-m-d H:i:s'));

        return $this->data;
    }

    /**
     * Send the XML request to the API server and process the response.
     *
     * @return array response data array
     */
    public function send()
    {
        $request = $this->client->post(
            $this->url,
            null,
            $this->getXml()
        );
        $request->setHeader('content-type', 'text/xml');

        //Process response data
        $response = new Response($request->send()->xml());
        return $response->process();
    }
}
