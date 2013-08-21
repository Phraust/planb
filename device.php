<?php
// unique data
//****************Setup*************
$url=$_SERVER[''];
header("Refresh: 3; URL=$url");
$model = 'BETA00001';
include ('db.php');
//****************Setup*************
//
?>
<?php
$sql = "SELECT * FROM address ORDER BY id DESC LIMIT 1"; $result = $link->query($sql);if ($result->num_rows > 0) {while($row = $result->fetch_assoc()) {$address = $row['address'];}}

$sql = $query = "SELECT SUM(b1) FROM bills"; $result = $sqlicon->query($sql);if ($result->num_rows > 0) {while($row = $result->fetch_assoc()) {$sum1 = $row['SUM(b1)'];}}
$sql = $query = "SELECT SUM(b2) FROM bills"; $result = $sqlicon->query($sql);if ($result->num_rows > 0) {while($row = $result->fetch_assoc()) {$sum2 = $row['SUM(b2)'];}}
$sql = $query = "SELECT SUM(b3) FROM bills"; $result = $sqlicon->query($sql);if ($result->num_rows > 0) {while($row = $result->fetch_assoc()) {$sum5 = $row['SUM(b3)'];}}
$sql = $query = "SELECT SUM(b4) FROM bills"; $result = $sqlicon->query($sql);if ($result->num_rows > 0) {while($row = $result->fetch_assoc()) {$sum10 = $row['SUM(b4)'];}}
$sql = $query = "SELECT SUM(b5) FROM bills"; $result = $sqlicon->query($sql);if ($result->num_rows > 0) {while($row = $result->fetch_assoc()) {$sum20 = $row['SUM(b5)'];}}
$sql = $query = "SELECT SUM(b6) FROM bills"; $result = $sqlicon->query($sql);if ($result->num_rows > 0) {while($row = $result->fetch_assoc()) {$sum50 = $row['SUM(b6)'];}}
$sql = $query = "SELECT SUM(b7) FROM bills"; $result = $sqlicon->query($sql);if ($result->num_rows > 0) {while($row = $result->fetch_assoc()) {$sum100 = $row['SUM(b7)'];}}
$total = ($sum1 *1) + ($sum5 * 5) + ($sum2 * 2) + ($sum10 * 10) + ($sum20 * 20) + ($sum50 *50) + ($sum100 * 100);
$sql = $query = "SELECT SUM(usd) FROM btcout";$result = $sqlicon->query($sql); if ($result->num_rows > 0) { while($row = $result->fetch_assoc()) {$BTCpaid = $row['SUM(usd)'];}}
$sestotal = $total - $BTCpaid;
/*/errors
if (!isset($BTCpaid)){ echo 'EMPTY BTC PAID ERROR****';}
if (!isset($total)){ echo 'EMPTY $total ERROR****';}
if (!isset($sestotal)){ echo 'EMPTY sestotal ERROR****';}
if ($sestotal < 0 ){ echo 'NEGIVITE sestotal ERROR****';}
/*/
//$data = json_decode(file_get_contents("http://blockchain.info/ticker"),true); $entry = round($data['USD']['last'], 2);

//gox
$c = curl_init();
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
curl_setopt($c, CURLOPT_URL, 'http://data.mtgox.com/api/2/BTCUSD/money/ticker');

$data = curl_exec($c);
curl_close($c);

$obj = json_decode($data);

$last = print_r($obj->{'data'}->{'last'}->{'display_short'}."\n", true);
$entry = filter_var($last, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
//echo $entry;

$query = "INSERT INTO price SET price = ?";$stmt = $con->prepare($query); $stmt->bindParam(1, $entry);$stmt->execute();
$sql = "SELECT * FROM price ORDER BY id DESC LIMIT 1"; $result = $link->query($sql); if ($result->num_rows > 0) {while($row = $result->fetch_assoc()) {$lastprice = $row['price'];}}
//
if ($sestotal < 0) {$sestotal = 0;}
if ($sestotal > 0) {
$sql = "SELECT * FROM timer ORDER BY id DESC LIMIT 1";$result = $link->query($sql);if ($result->num_rows > 0) {while($row = $result->fetch_assoc()) {$count = $row['count'];}}
$newcount = $count + 1;$query = "INSERT INTO timer SET count = $newcount";$stmt = $con->prepare($query);$stmt->bindParam(1, $newcount);$stmt->execute();}
//
if (!isset($count)){$count = '0';}
if (!isset($timer)){$time = 'paused';}

if ($count == 1) {$time = '30';}
///
if ($count == 2) {$time = '25';}
if ($count == 3) {$time = '20';}
if ($count == 4) {$time = '15';}
if ($count == 5) {$time = '10';}
if ($count == 6) {$time = '5';}
///
$txout =  $sestotal / $lastprice;
$satoshi = $txout * 100000000 ; 
$amount = round($satoshi, 3);
$sql = "SELECT * FROM address ORDER BY id DESC LIMIT 1"; $result = $link->query($sql);if ($result->num_rows > 0) {while($row = $result->fetch_assoc()) {$address = $row['address'];}}
$sql = "SELECT * FROM recipts ORDER BY id DESC LIMIT 1"; $result = $link->query($sql);if ($result->num_rows > 0) {while($row = $result->fetch_assoc()) {$recipt = $row['raw'];}}
//if ($count == 2) {
if ($count == 7) {

//***********
include ('BETA00001DB.php');


$ch = curl_init ("https://blockchain.info/merchant/$guid/payment?password=$main_password&second_password=$second_password&to=$address&amount=$amount");
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
$nrecipt = curl_exec ($ch);

$query = "INSERT INTO recipts SET raw = ?"; $stmt = $con->prepare($query); $stmt->bindParam(1, $nrecipt);$stmt->execute();
$sql = "SELECT * FROM address ORDER BY id DESC LIMIT 1"; $result = $link->query($sql);if ($result->num_rows > 0) {while($row = $result->fetch_assoc()) {$address = $row['address'];}}
if (empty($address)) {$address = 'empty error';}
$query = "INSERT INTO recipts SET body = ?, model = ?, amount = ?, toaddress = ?"; $stmt = $conr->prepare($query); 
$stmt->bindParam(1, $nrecipt);
$stmt->bindParam(2, $model);
$stmt->bindParam(3, $amount);
$stmt->bindParam(4, $address);
$stmt->execute();
$query = "INSERT INTO btcout SET usd = ?"; $stmt = $con->prepare($query); $stmt->bindParam(1, $sestotal);$stmt->execute();
$reset = '0';
$query = "INSERT INTO timer SET count = ?"; $stmt = $con->prepare($query); $stmt->bindParam(1, $reset);$stmt->execute();
//*******
	}
//******


/*/debug
echo 'last ptice: '.$lastprice.'<P>';
if (!isset ($error)) {echo 'no error'.'<P>';} else {echo 'error: '.$error.'<P>';};
echo 'BTC paid: '.$BTCpaid.'<P>';
echo 'sestotal: '.$sestotal.'<P>';
echo '<P>'.'Count: '.$count.'<P>';
echo 'timer: '.$time.'<P>';
echo 'recipts: '.$recipt.'<P>';
echo 'Total in device: '.$total.'<P>';
echo 'satoshi: '.$satoshi.'<P>';
echo 'Txout: '.$txout.'<P>';
echo 'Address: '.$address.'<P>';
echo 'Amount: '.$satoshi.'<P>';
/*/

//GUI settings
?>
	

	

    <!-- BEGIN GUI -->
     
    <!doctype html>
    <html>
     
    <head>
    <title>BitcoinATM</title>
    <link rel="stylesheet" type="text/css" href="https://www.phraust.com/BitcoinATM/style.css">
    </head>
     
    <body>
     
    <table width="100%" height="100%" cellspacing="0" border="0">
    <tr>
            <td colspan="3">
            <span class="white big-text high-shadow" data-text="TRASH YOUR OLD FIAT!">TRASH YOUR OLD FIAT!</span>
            <br>
            <span class="white small-text low-shadow" data-text="TIMER: <?php echo $time; ?>">TIMER: <?php echo $time; ?></span>
            </td>
    </tr>
    <tr>
            <td>
            <span class="white medium-text low-shadow" data-text="USD AMOUNT">USD AMOUNT</span>
            <br>
            <span class="green big-text high-shadow" data-text="$<?php echo round($sestotal, 3); ?>">$<?php echo round($sestotal, 3); ?></span>
            </td>
     
            <td width="350">
            <img src="https://www.phraust.com/BitcoinATM/BitcoinATM.png">
            </td>
           
            <td>
            <span class="white medium-text low-shadow" data-text="BTC AMOUNT">BTC AMOUNT</span>
            <br>
            <span class="brown big-text high-shadow" data-text="B<?php echo round($txout, 2); ?>">B<?php echo round($txout, 2); ?></span>
            </td>
    </tr>
    <tr>
            <td colspan="3">
                    <span class="white medium-text low-shadow" data-text="RECEIVING ADDRESS">Receiving Address</span>
                    <br>
                    <span class="brown small-text low-shadow" data-text="<?php echo $address; ?>"><?php echo $address; ?></span>
            </td>
    </tr>
    <tr>
            <td colspan="3">
                    <span class="tx gox white medium-text low-shadow" data-text="BITCOIN SENT!">Bitcoin Sent!</span><br>
                    <span class="tx white medium-text low-shadow" data-text="TXID: <?php echo $recipt[2]; ?>">TXID: <?php echo $recipt[2]; ?></span>
            </td>
    </tr>
    <tr height="10%">
            <td colspan="2" class="footer left">
            <span class="white small-text low-shadow" data-text="1 BITCOIN =">1 Bitcoin =</span> <span class="green small-text low-shadow gox" data-text="$<?php echo $lastprice; ?>">$<?php echo $lastprice; ?></span>
            </td>
     
            <td colspan="2" class="footer right">
            <span class="white small-text low-shadow" data-text="PRICING BY">Pricing by</span> <span class="white small-text low-shadow gox" data-text="MT.GOX">Mt.Gox</span>
            </td>
    </tr>
    </table>
     
    <div id="debug">
    <p>
    Model:<br>
    <?php echo $model; ?>
    </p>
     
    <p>
    Session Total USD to be sent:<br>
    <?php echo $sestotal; ?>
    </p>
     
    <p>
    Total USD in device:<br>
    <?php echo $total; ?>
    </p>
     
    <p>
    Total BTC paid out by device (in USD):<br>
    <?php echo $BTCpaid; ?>
    </p>
     
    <p>
    Last Receipt:<br>
    <?php echo $nrecipt; ?>
    </p>
    </div>
     
    </body>
    </html>
     
    <!-- END GUI -->
