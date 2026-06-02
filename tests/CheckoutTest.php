<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/Checkout.php';

use App\Checkout;

class CheckoutTest extends TestCase
{
    private $produkFile;
    private $pesananFile;

    protected function setUp(): void
    {
        $this->produkFile = __DIR__ . '/products_test.json';
        $this->pesananFile = __DIR__ . '/orders_test.json';

        $products = [
            "PRD-001" => [
                "nama" => "Kemeja Flanel",
                "harga" => 150000,
                "stok" => 20
            ],
            "PRD-002" => [
                "nama" => "Celana Jeans",
                "harga" => 250000,
                "stok" => 20
            ]
        ];

        file_put_contents(
            $this->produkFile,
            json_encode($products)
        );

        file_put_contents(
            $this->pesananFile,
            json_encode([])
        );
    }

    // PATH 1
    public function testCheckoutNormal()
    {
        $checkout = new Checkout(
            $this->produkFile,
            $this->pesananFile
        );

        $keranjang = [
            'PRD-001' => 2
        ];

        $hasil = $checkout->prosesCheckout(
            'budi@email.com',
            'Alamat Test',
            $keranjang
        );

        $this->assertEquals(
            320000,
            $hasil['total_bayar']
        );
    }

    // PATH 2
    public function testGratisOngkir()
    {
        $checkout = new Checkout(
            $this->produkFile,
            $this->pesananFile
        );

        $keranjang = [
            'PRD-001' => 2,
            'PRD-002' => 1
        ];

        $hasil = $checkout->prosesCheckout(
            'budi@email.com',
            'Alamat Test',
            $keranjang
        );

        $this->assertEquals(
            550000,
            $hasil['total_bayar']
        );
    }

    // PATH 3
    public function testDiskon()
    {
        $checkout = new Checkout(
            $this->produkFile,
            $this->pesananFile
        );

        $keranjang = [
            'PRD-002' => 5
        ];

        $hasil = $checkout->prosesCheckout(
            'budi@email.com',
            'Alamat Test',
            $keranjang
        );

        $this->assertEquals(
            1125000,
            $hasil['total_bayar']
        );
    }
}