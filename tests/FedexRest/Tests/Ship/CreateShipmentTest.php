<?php declare(strict_types=1);

namespace FedexRest\Tests\Ship;

use FedexRest\Authorization\Authorize;
use FedexRest\Entity\Address;
use FedexRest\Entity\Dimensions;
use FedexRest\Entity\Item;
use FedexRest\Exceptions\MissingAccessTokenException;
use FedexRest\Exceptions\MissingAuthCredentialsException;
use FedexRest\Exceptions\MissingLineItemException;
use FedexRest\Services\Ship\Entity\Label;
use FedexRest\Entity\Person;
use FedexRest\Services\Ship\Entity\ShippingChargesPayment;
use FedexRest\Entity\Weight;
use FedexRest\Exceptions\MissingAccountNumberException;
use FedexRest\Services\Ship\Exceptions\MissingLabelException;
use FedexRest\Services\Ship\Exceptions\MissingLabelResponseOptionsException;
use FedexRest\Services\Ship\Exceptions\MissingShippingChargesPaymentException;
use FedexRest\Services\Ship\CreateShipment;
use FedexRest\Services\Ship\Type\ImageType;
use FedexRest\Services\Ship\Type\LabelDocOptionType;
use FedexRest\Services\Ship\Type\LabelResponseOptionsType;
use FedexRest\Services\Ship\Type\LabelStockType;
use FedexRest\Services\Ship\Type\LinearUnits;
use FedexRest\Services\Ship\Type\PackagingType;
use FedexRest\Services\Ship\Type\PickupType;
use FedexRest\Services\Ship\Type\ServiceType;
use FedexRest\Services\Ship\Type\WeightUnits;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class CreateShipmentTest extends TestCase
{
    protected Authorize $auth;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->auth = (new Authorize)
            ->setClientId('l7749d031872cf4b55a7889376f360d045')
            ->setClientSecret('bd59d91084e8482895d4ae2fb4fb79a3');
    }

    public function testHasAccountNumber()
    {
        $request = NULL;
        try {

            $request = (new CreateShipment)
                ->setAccessToken((string) $this->auth->authorize()->access_token)
                ->request();

        } catch (MissingAccountNumberException $e) {
            $this->assertEquals('The account number is required', $e->getMessage());
        }
        $this->assertEmpty($request, 'The request did not fail as it should.');
    }

    public function testLabelResponseOptions()
    {
        $request = NULL;
        try {
            $request = (new CreateShipment())
                ->setAccessToken((string) $this->auth->authorize()->access_token)
                ->setAccountNumber(740561073)
                ->setServiceType(ServiceType::_FEDEX_GROUND)
                ->setPackagingType(PackagingType::_YOUR_PACKAGING)
                ->setPickupType(PickupType::_DROPOFF_AT_FEDEX_LOCATION)
                ->setShippingChargesPayment((new ShippingChargesPayment())
                    ->setPaymentType('SENDER')
                )
                ->setShipDatestamp((new \DateTime())->add(new \DateInterval('P3D'))->format('Y-m-d'))
                ->setLabel((new Label())
                    ->setLabelStockType(LabelStockType::_STOCK_4X6)
                    ->setImageType(ImageType::_PDF)
                )
                ->setShipper(
                    (new Person)
                        ->setPersonName('SHIPPER NAME')
                        ->setPhoneNumber('1234567890')
                        ->withAddress(
                            (new Address())
                                ->setCity('Collierville')
                                ->setStreetLines('RECIPIENT STREET LINE 1')
                                ->setStateOrProvince('TN')
                                ->setCountryCode('US')
                                ->setPostalCode('38017')
                        )
                )
                ->setRecipients(
                    (new Person)
                        ->setPersonName('RECEIPIENT NAME')
                        ->setPhoneNumber('1234567890')
                        ->withAddress(
                            (new Address())
                                ->setCity('Irving')
                                ->setStreetLines('RECIPIENT STREET LINE 1')
                                ->setStateOrProvince('TX')
                                ->setCountryCode('US')
                                ->setPostalCode('75063')
                        )
                )
                ->setLineItems((new Item())
                    ->setItemDescription('lorem Ipsum')
                    ->setWeight(
                        (new Weight())
                            ->setValue(1)
                            ->setUnit(WeightUnits::_POUND)
                    )
                    ->setDimensions((new Dimensions())
                        ->setWidth(12)
                        ->setLength(12)
                        ->setHeight(12)
                        ->setUnits(LinearUnits::_INCH)
                    )
                )->request();
        } catch (MissingLabelResponseOptionsException $e) {
            $this->assertEquals('Label Response Options are required', $e->getMessage());
        }
        $this->assertEmpty($request, 'The request did not fail as it should.');
    }

    public function testShippingChargesPayment()
    {
        $request = NULL;
        try {
            $request = (new CreateShipment())
                ->setAccessToken((string) $this->auth->authorize()->access_token)
                ->setAccountNumber(740561073)
                ->setServiceType(ServiceType::_FEDEX_GROUND)
                ->setPackagingType(PackagingType::_YOUR_PACKAGING)
                ->setPickupType(PickupType::_DROPOFF_AT_FEDEX_LOCATION)
                ->setLabelResponseOptions(LabelResponseOptionsType::_URL_ONLY)
                ->setShipDatestamp((new \DateTime())->add(new \DateInterval('P3D'))->format('Y-m-d'))
                ->setLabel((new Label())
                    ->setLabelStockType(LabelStockType::_STOCK_4X6)
                    ->setImageType(ImageType::_PDF)
                )
                ->setShipper(
                    (new Person)
                        ->setPersonName('SHIPPER NAME')
                        ->setPhoneNumber('1234567890')
                        ->withAddress(
                            (new Address())
                                ->setCity('Collierville')
                                ->setStreetLines('RECIPIENT STREET LINE 1')
                                ->setStateOrProvince('TN')
                                ->setCountryCode('US')
                                ->setPostalCode('38017')
                        )
                )
                ->setRecipients(
                    (new Person)
                        ->setPersonName('RECEIPIENT NAME')
                        ->setPhoneNumber('1234567890')
                        ->withAddress(
                            (new Address())
                                ->setCity('Irving')
                                ->setStreetLines('RECIPIENT STREET LINE 1')
                                ->setStateOrProvince('TX')
                                ->setCountryCode('US')
                                ->setPostalCode('75063')
                        )
                )
                ->setLineItems((new Item())
                    ->setItemDescription('lorem Ipsum')
                    ->setWeight(
                        (new Weight())
                            ->setValue(1)
                            ->setUnit(WeightUnits::_POUND)
                    )
                    ->setDimensions((new Dimensions())
                        ->setWidth(12)
                        ->setLength(12)
                        ->setHeight(12)
                        ->setUnits(LinearUnits::_INCH)
                    )
                )->request();
        } catch (MissingShippingChargesPaymentException $e) {
            $this->assertEquals('Shipping charges payment is required', $e->getMessage());
        }
        $this->assertEmpty($request, 'The request did not fail as it should.');
    }

    public function testLabel()
    {
        $request = NULL;
        try {
            $request = (new CreateShipment)
                ->setAccessToken((string) $this->auth->authorize()->access_token)
                ->setAccountNumber(740561073)
                ->setServiceType(ServiceType::_FEDEX_GROUND)
                ->setLabelResponseOptions(LabelResponseOptionsType::_URL_ONLY)
                ->setPackagingType(PackagingType::_YOUR_PACKAGING)
                ->setPickupType(PickupType::_DROPOFF_AT_FEDEX_LOCATION)
                ->setShippingChargesPayment((new ShippingChargesPayment())
                    ->setPaymentType('SENDER')
                )
                ->setRecipients(
                    (new Person)
                        ->setPersonName('Lorem')
                        ->setPhoneNumber('1234567890')
                        ->withAddress(
                            (new Address())
                                ->setCity('Boston')
                                ->setStreetLines('line 1', 'line 2')
                                ->setStateOrProvince('MA')
                                ->setCountryCode('US')
                                ->setPostalCode('55555')
                        )
                )
                ->setShipper(
                    (new Person)
                        ->setPersonName('Ipsum')
                        ->setPhoneNumber('1234567890')
                )
                ->setLineItems((new Item())
                    ->setItemDescription('lorem Ipsum')
                    ->setWeight(
                        (new Weight())
                            ->setValue(1)
                            ->setUnit(WeightUnits::_POUND)
                    )
                    ->setDimensions((new Dimensions())
                        ->setWidth(12)
                        ->setLength(12)
                        ->setHeight(12)
                        ->setUnits(LinearUnits::_INCH)
                    ))->request();
        } catch (MissingLabelException $e) {
            $this->assertEquals('A label is required', $e->getMessage());
        }
        $this->assertEmpty($request, 'The request did not fail as it should.');
    }

    public function testRequiredData()
    {
        $shipment = (new CreateShipment())
            ->setAccessToken((string) $this->auth->authorize()->access_token)
            ->setAccountNumber(740561073)
            ->setServiceType(ServiceType::_FEDEX_GROUND)
            ->setLabelResponseOptions(LabelResponseOptionsType::_URL_ONLY)
            ->setShippingChargesPayment((new ShippingChargesPayment())
                ->setPaymentType('SENDER')
            )
            ->setLabel((new Label())
                ->setLabelStockType(LabelStockType::_STOCK_4X6)
                ->setImageType(ImageType::_PDF)
            )
            ->setRecipients(
                (new Person)->setPersonName('Lorem')
                    ->withAddress(
                        (new Address())
                            ->setCity('Boston')
                            ->setStreetLines('line 1', 'line 2')
                    ),
                (new Person)->setPersonName('Ipsum')
            )
            ->setShipper(
                (new Person)->setPersonName('Ipsum')
            );
        $requested_shipment = $shipment->getRequestedShipment();
        $this->assertCount(2, $requested_shipment['recipients']);
        $this->assertNotEmpty($requested_shipment['shipper']['contact']['personName']);
        $this->assertEquals(LabelResponseOptionsType::_URL_ONLY, $shipment->getLabelResponseOptions());
        $this->assertEquals(LabelDocOptionType::_LABELS_AND_DOCS, $shipment->getMergeLabelDocOption());
        $this->assertEquals('FEDEX_GROUND', $requested_shipment['serviceType']);
    }

    public function testPrepare()
    {
        $request = (new CreateShipment)
            ->setAccessToken((string) $this->auth->authorize()->access_token)
            ->setAccountNumber(740561073)
            ->setServiceType(ServiceType::_FEDEX_GROUND)
            ->setLabelResponseOptions(LabelResponseOptionsType::_URL_ONLY)
            ->setPackagingType(PackagingType::_YOUR_PACKAGING)
            ->setPickupType(PickupType::_DROPOFF_AT_FEDEX_LOCATION)
            ->setLabel((new Label())
                ->setLabelStockType(LabelStockType::_STOCK_4X6)
                ->setImageType(ImageType::_PDF)
            )
            ->setShippingChargesPayment((new ShippingChargesPayment())
                ->setPaymentType('SENDER')
            )
            ->setRecipients(
                (new Person)
                    ->setPersonName('Lorem')
                    ->setPhoneNumber('1234567890')
                    ->withAddress(
                        (new Address())
                            ->setCity('Boston')
                            ->setStreetLines('line 1', 'line 2')
                            ->setStateOrProvince('MA')
                            ->setCountryCode('US')
                            ->setPostalCode('55555')
                    )
            )
            ->setShipper(
                (new Person)
                    ->setPersonName('Ipsum')
                    ->setPhoneNumber('1234567890')
            )
            ->setLineItems((new Item())
                ->setItemDescription('lorem Ipsum')
                ->setWeight(
                    (new Weight())
                        ->setValue(1)
                        ->setUnit(WeightUnits::_POUND)
                )
                ->setDimensions((new Dimensions())
                    ->setWidth(12)
                    ->setLength(12)
                    ->setHeight(12)
                    ->setUnits(LinearUnits::_INCH)
                ));
        $prepared = $request->prepare();
        $requested_shipment = $prepared['requestedShipment'];
        $this->assertEquals('Boston', $requested_shipment['recipients'][0]['address']['city']);
        $this->assertCount(1, $requested_shipment['recipients']);
        $this->assertNotEmpty($requested_shipment['shipper']['contact']['personName']);
        $this->assertEquals(LabelResponseOptionsType::_URL_ONLY, $prepared['labelResponseOptions']);
        $this->assertEquals(LabelDocOptionType::_LABELS_AND_DOCS, $prepared['mergeLabelDocOption']);
        $this->assertEquals(FALSE, $prepared['oneLabelAtATime']);
        $this->assertEquals('FEDEX_GROUND', $requested_shipment['serviceType']);
    }

    /**
     * @throws MissingLabelResponseOptionsException
     * @throws MissingShippingChargesPaymentException
     * @throws MissingAuthCredentialsException
     * @throws MissingLineItemException
     * @throws MissingAccessTokenException
     * @throws MissingLabelException
     * @throws GuzzleException
     * @throws MissingAccountNumberException
     */
    public function testRequest()
    {
        $shipment = (new CreateShipment())
            ->setAccessToken((string) $this->auth->authorize()->access_token)
            ->setAccountNumber(740561073)
            ->setServiceType(ServiceType::_FEDEX_GROUND)
            ->setLabelResponseOptions(LabelResponseOptionsType::_URL_ONLY)
            ->setPackagingType(PackagingType::_YOUR_PACKAGING)
            ->setPickupType(PickupType::_DROPOFF_AT_FEDEX_LOCATION)
            ->setShippingChargesPayment((new ShippingChargesPayment())
                ->setPaymentType('SENDER')
            )
            ->setShipDatestamp((new \DateTime())->add(new \DateInterval('P3D'))->format('Y-m-d'))
            ->setLabel((new Label())
                ->setLabelStockType(LabelStockType::_STOCK_4X6)
                ->setImageType(ImageType::_PDF)
            )
            ->setShipper(
                (new Person)
                    ->setPersonName('SHIPPER NAME')
                    ->setPhoneNumber('1234567890')
                    ->withAddress(
                        (new Address())
                            ->setCity('Collierville')
                            ->setStreetLines('RECIPIENT STREET LINE 1')
                            ->setStateOrProvince('TN')
                            ->setCountryCode('US')
                            ->setPostalCode('38017')
                    )
            )
            ->setRecipients(
                (new Person)
                    ->setPersonName('RECEIPIENT NAME')
                    ->setPhoneNumber('1234567890')
                    ->withAddress(
                        (new Address())
                            ->setCity('Irving')
                            ->setStreetLines('RECIPIENT STREET LINE 1')
                            ->setStateOrProvince('TX')
                            ->setCountryCode('US')
                            ->setPostalCode('75063')
                    )
            )
            ->setLineItems((new Item())
                ->setItemDescription('lorem Ipsum')
                ->setWeight(
                    (new Weight())
                        ->setValue(1)
                        ->setUnit(WeightUnits::_POUND)
                )
                ->setDimensions((new Dimensions())
                    ->setWidth(12)
                    ->setLength(12)
                    ->setHeight(12)
                    ->setUnits(LinearUnits::_INCH)
                )
            );
        $request = $shipment->request();
        $this->assertObjectHasProperty('transactionId', $request);
        $this->assertObjectNotHasProperty('errors', $request);
        $this->assertObjectHasProperty('output', $request);
        $output = $request->output;
        $this->assertNotEmpty($output->transactionShipments);
        $new_shipment = $output->transactionShipments[0];
        $this->assertNotEmpty($new_shipment->masterTrackingNumber);
        $this->assertEquals('FEDEX_GROUND', $new_shipment->serviceType);
        $this->assertNotEmpty($new_shipment->pieceResponses);
        $this->assertNotEmpty($new_shipment->completedShipmentDetail);
        $this->assertEquals('EXPRESS', $new_shipment->serviceCategory);
    }

}
