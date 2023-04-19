<?php
include "database.php";


/************************** -Create- **************************/
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    /************************** -Veri Kontrol- **************************/
    $customerId = addslashes(isset($_POST['customerId']) ? $_POST['customerId'] : '');
    $items = isset($_POST['items']) ? $_POST['items'] : '';
    $mainTotal = addslashes(isset($_POST['total']) ? $_POST['total'] : '');
    if ($customerId != "" and $items != "" and $mainTotal != "") {
        /************************** -Veri Kontrol- **************************/
        $discounts2 = 0;
        $discounts3 = 0;
        $pricess = [];
        $discounts = [];
        $data = json_decode($items, true);
        foreach ($data as $item) { //Gelen Sipariş içerisindeki ürünlerin veri
            $productId = $item['productId'];
            $quantity = $item['quantity'];
            $unitPrice = $item['unitPrice'];
            array_push($pricess, $unitPrice);
            $total = $item['total'];

            $query = $database->prepare("SELECT * FROM products where id = '$productId'"); //Gelen Sipariş içerisindeki ürün verilerinin ürün tablosundan çekilmesi
            $query->execute();
            while ($row = $query->fetch()) {
                $category = $row['category'];
                $stock = $row['stock'];
                $price = $row['price'];

                /* Yeni sipariş eklenirken, satın alınan ürünün stoğu yeterli değilse (products.stock) bir hata mesajı döndürün. */
                if ($quantity > $stock) {
                    $stock_status = false;
                    break;
                }
                /* Yeni sipariş eklenirken, satın alınan ürünün stoğu yeterli değilse (products.stock) bir hata mesajı döndürün. */

                /* 2 ID'li kategoriye ait bir üründen 6 adet satın alındığında, bir tanesi ücretsiz olarak verilir. */
                if ($category == 2) {
                    $discounts2 = $discounts2 + $quantity;
                    $discounts2ProductPrice = $price;
                }
                /* 2 ID'li kategoriye ait bir üründen 6 adet satın alındığında, bir tanesi ücretsiz olarak verilir. */

                /* 1 ID'li kategoriden iki veya daha fazla ürün satın alındığında, en ucuz ürüne %20 indirim yapılır. */
                if ($category == 1) {
                    $discounts3 = $discounts3 + $quantity;
                }
                /* 1 ID'li kategoriden iki veya daha fazla ürün satın alındığında, en ucuz ürüne %20 indirim yapılır. */
            }
        }

        /* Yeni sipariş eklenirken, satın alınan ürünün stoğu yeterli değilse (products.stock) bir hata mesajı döndürün. */
        if (!isset($stock_status)) {
            $stock_status = true;
        }
        /* Yeni sipariş eklenirken, satın alınan ürünün stoğu yeterli değilse (products.stock) bir hata mesajı döndürün. */
        if ($stock_status) {
            /* Toplam 1000TL ve üzerinde alışveriş yapan bir müşteri, siparişin tamamından %10 indirim kazanır. */
            $totalDiscount = 0;
            if ($mainTotal >= 1000) {
                $discountReason = "10_PERCENT_OVER_1000";
                $discountAmount = $mainTotal * 10 / 100;
                $subtotal =  $mainTotal -  $discountAmount;
                $discountArray1 = array("discountReason" => $discountReason, "discountAmount" => $discountAmount, "subtotal" => $subtotal);
                array_push($discounts, $discountArray1);
                $totalDiscount += $discountAmount;
                $mainTotal = $subtotal;
            }
            /* Toplam 1000TL ve üzerinde alışveriş yapan bir müşteri, siparişin tamamından %10 indirim kazanır. */

            /* 2 ID'li kategoriye ait bir üründen 6 adet satın alındığında, bir tanesi ücretsiz olarak verilir. */
            if ($discounts2 >= 6) {
                $discountReason = "BUY_6_GET_1";
                $discountAmount = $discounts2ProductPrice;
                $subtotal =  $mainTotal -  $discountAmount;
                $discountArray2 = array("discountReason" => $discountReason, "discountAmount" => $discountAmount, "subtotal" => $subtotal);
                array_push($discounts, $discountArray2);
                $totalDiscount += $discountAmount;
                $mainTotal = $subtotal;
            }
            /* 2 ID'li kategoriye ait bir üründen 6 adet satın alındığında, bir tanesi ücretsiz olarak verilir. */


            /* 1 ID'li kategoriden iki veya daha fazla ürün satın alındığında, en ucuz ürüne %20 indirim yapılır. */
            if ($discounts3 >= 2) {
                $discountReason = "BUY_2_GET_1";
                sort($pricess);
                $discountAmount = $pricess[0] * 20 / 100;
                $subtotal =  $mainTotal -  $discountAmount;
                $discountArray3 = array("discountReason" => $discountReason, "discountAmount" => $discountAmount, "subtotal" => $subtotal);
                array_push($discounts, $discountArray3);
                $totalDiscount += $discountAmount;
            }
            /* 1 ID'li kategoriden iki veya daha fazla ürün satın alındığında, en ucuz ürüne %20 indirim yapılır. */


            $query = $database->prepare("INSERT INTO orders(customerId,items,total) VALUES(?,?,?)");
            $query = $query->execute(array($customerId, $items, $total));
            if ($query) {
                $row = array("message" => "Sipariş Oluşturuldu", "success" => true, "discounts" => $discounts, "totalDiscount" => $totalDiscount, "discountedTotal" => $subtotal);
            } else {
                $row = array("message" => "Sipariş Alınamadı Lütfen Daha Sonra Tekrar Deneyin!", "success" => false);
            }
        } else {
            $row = array("message" => "Yetersiz Stok!", "success" => false);
        }
    } else {
        $row = array("message" => "Lütfen Zorunlu Alanları Doldurunuz!", "success" => false);
    }
    $result[] = $row;
    print_r(json_encode($result, JSON_UNESCAPED_UNICODE));
}
/************************** -Create- **************************/

/************************** -Delete- **************************/

else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    $id = addslashes(isset($_GET['id']) ? $_GET['id'] : '');
    if ($id != "") {
        $stmt = $database->prepare("SELECT * FROM orders WHERE id = '$id'");
        $stmt->execute();
        $query = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($query > 0) {
            $query = $database->exec("DELETE FROM orders WHERE id = '$id'");
            if ($query) {
                $row = array("message" => "Kayıt Silindi!", "success" => true);
            } else {
                $row = array("message" => "Kayıt Silinemedi Lütfen Daha Tekrar Deneyiniz!", "success" => false);
            }
        } else {
            $row = array("message" => "Kayıt Bulunamadı Lütfen ID Kontrol Ediniz!", "success" => false);
        }
    } else {
        $row = array("message" => "Lütfen Zorunlu Alanları Doldurunuz!", "success" => false);
    }
    $result[] = $row;
    print_r(json_encode($result, JSON_UNESCAPED_UNICODE));
}
/************************** -Delete- **************************/

/************************** -VIEW- **************************/
else if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $stmt = $database->prepare("SELECT * FROM orders ORDER BY id ASC");
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $id = $row['id'];
        $customerId = $row['customerId'];
        $items = json_decode($row['items']);
        $total = $row['total'];
        $row = array("id" => $id, "customerId" => $customerId, "items" => $items, "total" => $total);
        $result[] = $row;
    }
    print_r(json_encode($result, JSON_UNESCAPED_UNICODE));
}
/************************** -VIEW- **************************/

/************************** -NULL- **************************/
else {
    $row = array("message" => "Geçersiz method!", "success" => false);
    $result[] = $row;
    print_r(json_encode($result, JSON_UNESCAPED_UNICODE));
}
/************************** -NULL- **************************/
