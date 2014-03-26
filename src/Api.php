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

/**
 * Api
 *
 * This is the main class that sets the inital auth settings.
 * It will build the request array and will trigger the API request.
 *
 * Example usage:
 * 
 * $charge = Api::create('username', 'password')
 *     ->content('999999', 'The content description goes here')
 *     ->phone('35387123456')
 *     ->charge('100', 'This is a test transaction')
 *     ->send();
 * 
 * @package MobileBill
 * @author  Ciaran Maher
 * @since   1.0.0
 */
class Api
{
    /**
     * The Base URL for the API
     *
     * @const string
     */
    const BASE_URL = 'https://www.allpointsmessaging.com/mno/api';

    /**
     * The Guzzle object
     *
     * @type \Guzzle\Http\Client
     */
    private $client;

    /**
     * The Request object
     *
     * @type \Square1\MobileBill\Request
     */
    private $request;

    /**
     * 2Bill Construtor
     *
     * @param \Guzzle\Http\Client         $client  The Guzzle client
     * @param \Square1\MobileBill\Request $request The request object
     */
    public function __construct(Client $client, Request $request)
    {
        $this->request = $request;
        $this->client = $client;
        $this->client->setBaseUrl(self::BASE_URL);
    }

    /**
     * This is a easy-to-use facade for using this class.
     * 
     * @param string $username  The 2Bill username 
     * @param string $password  The 2Bill password
     *
     * @return \Square1\MobileBill\Api
     */
    public static function create($username, $password)
    {
        //Guzzle client
        $client = new Client();

        //Request object
        $request = new Request($client);

        $request->set('USERNAME', $username);
        $request->set('PASSWORD', $password);

        return new Api($client, $request);
    }

    /**
     * Set the content id and description
     *
     * @param int    $contentId          The content id
     * @param string $contentDescription The content description
     * 
     * @return \Square1\MobileBill\Api
     */
    public function content($contentId, $contentDescription)
    {
        //Set client id and description
        $this->request->set('CONTENTID', $contentId);
        $this->request->set('CONTENTDESCRIPTION', $contentDescription);

        return $this;
    }

    /**
     * Set the users phone number (& optional operator id)
     *
     * @param int $phoneNumber The 12 digit mobile number, ie '353871234567'
     * @param int $operatorId  The operator id (optional)
     *
     * @return \Square1\MobileBill\Api
     */
    public function phone($phoneNumber, $operatorId = '')
    {
        $this->request->set('MSISDN', $phoneNumber);

        //Set operator id
        if (!empty($operatorId)) {
            $this->request->set('OPERATORID', $operatorId);
        }

        return $this;
    }

    /**
     * Charge a mobile phone
     *
     * @param int    $amount    The transaction amount in cents (max '3000')
     * @param string $reference The user reference
     * @param int    $channel   The payment channel (default '1')
     * @param string $currency  The currecny code (default 'EUR')
     *
     * @return \Square1\MobileBill\Api
     */
    public function charge($amount, $reference, $channel = '1', $currency = 'EUR')
    {
        $charge = new ChargeApi($this->request);

        $charge->create(
            array(
                'TRANSACTIONAMOUNT' => $amount,
                'REFERENCE'         => $reference,
                'CHANNEL'           => $channel,
                'CURRENCYCODE'      => $currency
            )
        );

        return $this;
    }

    /**
     * Trigger the API request
     *
     * @return array response array
     */
    public function send()
    {
        return $this->request->send();
    }
}
