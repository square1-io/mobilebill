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

use Square1\MobileBill\Exception\AuthenticationError;
use Square1\MobileBill\Exception\ConnectionError;
use Square1\MobileBill\Exception\InvalidNumber;
use Square1\MobileBill\Exception\InvalidRequest;
use Square1\MobileBill\Exception\TransactionError;

/**
 * Response
 *
 * This class handles the response from the API and any error handling.
 *
 * @package MobileBill
 * @author  Ciaran Maher
 * @since   1.0.0
 */
class Response
{
    /**
     * The response data array
     *
     * @type array data
     */
    private $data;

    /**
     * Response Construtor
     *
     * @param \SimpleXMLElement $data The response string in xml format
     */
    public function __construct(\SimpleXMLElement $data)
    {
        //Convert from XML string into array
        $this->data = json_decode(json_encode($data), true);
    }

    /**
     * Validate and return the request response.
     *
     * This will process the response codes and, if required, will trigger an
     * appropriate exception outlined in the API docs @link http://bit.ly/1fTu6zb
     * 
     * @throws InvalidRequest
     * @throws AuthenticationError
     * @throws InvalidNumber
     * @throws ConnectionError
     * @throws TransactionError
     * @throws Exception
     *
     * @return array The response array
     */
    public function process()
    {
        switch($this->data['RESPONSECODE']) {
            case '1600':
            case '1601':
                break;

            case '1001':
            case '1002':
            case '1003':
            case '1004':
            case '1005':
            case '1006':
            case '1007':
            case '1008':
            case '1009':
            case '1031':
            case '1032':
            case '1033':
            case '1034':
            case '1035':
            case '1101':
            case '1103':
            case '1105':
            case '1106':
            case '1107':
            case '1108':
            case '1109':
            case '1131':
            case '1132':
            case '1133':
            case '1134':
            case '1135':
            case '1300':
            case '1301':
            case '1302':
            case '1303':
            case '1304':
            case '1305':
            case '1306':
            case '1307':
            case '1308':
            case '1309':
                throw new InvalidRequest(
                    sprintf(
                        '%s (#%s)',
                        $this->data['RESPONSETEXT'],
                        $this->data['RESPONSECODE']
                    )
                );

            case '1200':
            case '1201':
            case '1202':
                throw new AuthenticationError(
                    sprintf(
                        '%s (#%s)',
                        $this->data['RESPONSETEXT'],
                        $this->data['RESPONSECODE']
                    )
                );

            case '1400':
            case '1401':
            case '1402':
            case '1500':
                throw new ConnectionError(
                    sprintf(
                        '%s (#%s)',
                        $this->data['RESPONSETEXT'],
                        $this->data['RESPONSECODE']
                    )
                );

            case '1102':
            case '1501':
            case '1502':
            case '1503':
            case '1506':
                throw new InvalidNumber(
                    sprintf(
                        '%s (#%s)',
                        $this->data['RESPONSETEXT'],
                        $this->data['RESPONSECODE']
                    )
                );

            case '1504':
            case '1505':
            case '1507':
            case '1508':
                throw new TransactionError(
                    sprintf(
                        '%s (#%s)',
                        $this->data['RESPONSETEXT'],
                        $this->data['RESPONSECODE']
                    )
                );

            default:
                throw new \Exception(
                    sprintf(
                        '%s (#%s)',
                        $this->data['RESPONSETEXT'],
                        $this->data['RESPONSECODE']
                    )
                );
        }

        return $this->data;
    }
}
