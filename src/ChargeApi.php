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

/**
 * Charge
 *
 * This class will build the request array to charge a transaction to a mobile phone.
 * It will trigger the API request.
 * 
 * @package MobileBill
 * @author  Ciaran Maher
 * @since   1.0.0
 */
class ChargeApi
{
    /**
     * The URL endpoint for this request
     *
     * @const string
     */
    const URL = 'chargetobill.htm';

    /**
     * The name of the opening XML tags
     *
     * @const string
     */
    const XML = '<CHARGETOBILLREQUEST/>';

    /**
     * The Request object
     *
     * @type \Square1\MobileBill\Request
     */
    private $request;

    /**
     * Construtor
     *
     * @param \Square1\MobileBill\Request $request      The request object
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->request->setXml(self::XML);
        $this->request->setUrl(self::URL);
    }

    /**
     * Get the URL endpoint
     *
     * @return string Url endpoint
     */
    public function getUrl()
    {
        return self::URL;
    }

    /**
     * Load charge data and make API call
     *
     * @param array $data The charge data array
     */
    public function create(array $data)
    {
        $this->request->set('TRANSACTIONAMOUNT', $data['TRANSACTIONAMOUNT']);
        $this->request->set('REFERENCE', $data['REFERENCE']);
        $this->request->set('CHANNEL', $data['CHANNEL']);
        $this->request->set('CURRENCYCODE', $data['CURRENCYCODE']);
    }
}
