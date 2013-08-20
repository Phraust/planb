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
/*/Blockchain.info
$data = json_decode(file_get_contents("http://blockchain.info/ticker"),true); $entry = round($data['USD']['last'], 2);
/*/MTgox
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
/*/
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

//if ($count == 2) {
if ($count == 7) {

//***********
$txout =  $sestotal / $lastprice;
$satatshi = $txout * 100000000 ; 
$amount = round($satatshi);
//Blockchain.info API
//****************Setup*************
$guid="";
$main_password="";
$second_password="";
//****************Setup*************

$ch = curl_init ("https://blockchain.info/merchant/$guid/payment?password=$main_password&second_password=$second_password&to=$address&amount=$amount");
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
$nrecipt = curl_exec ($ch);
//END Blockchain.info API
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

$sql = "SELECT * FROM address ORDER BY id DESC LIMIT 1"; $result = $link->query($sql);if ($result->num_rows > 0) {while($row = $result->fetch_assoc()) {$address = $row['address'];}}
$sql = "SELECT * FROM recipts ORDER BY id DESC LIMIT 1"; $result = $link->query($sql);if ($result->num_rows > 0) {while($row = $result->fetch_assoc()) {$recipt = $row['raw'];}}
/*/debug
echo 'last ptice: '.$lastprice.'<P>';
if (!isset ($error)) {echo 'no error'.'<P>';} else {echo 'error: '.$error.'<P>';};
echo 'BTC paid: '.$BTCpaid.'<P>';
echo 'sestotal: '.$sestotal.'<P>';
echo '<P>'.'Count: '.$count.'<P>';
echo 'timer: '.$time.'<P>';
echo 'recipts: '.$recipt.'<P>';
echo 'Total in device: '.$total.'<P>';
/*/

//GUI settings

?>
<!--        START GUI ATM                                      -->

<?php
//
$bordercolor = '#FC0';

$style = 'classic bordered';
// if style = 'classic bordered'
if ($style == 'classic bordered') {
?>

<style>
  body {
    margin:9px 9px 0 9px;
    padding:0;
    background:#FFF;}
  #level0 {
    background:<?php echo $bordercolor; ?>;}
  #level1 {
    margin-left:143px;
    padding-left:9px;
    background:#FFF;}
  #level2 {
    background:<?php echo $bordercolor; ?>;}
  #level3 {
    margin-right:143px;
    padding-right:9px;
    background:#FFF;}
  #main {
    background:#FFF;}
</style>


<body>
  <div id="level0">
    <div id="level1">
      <div id="level2">
        <div id="level3">
          <div id="main">
         
<?php } ?>    

 <h1>Conversion rate: 1 (BTC) = $<?php echo $lastprice; ?>USD </h1>
<br /> 

<h1><?php echo $nrecipt; ?> </h1>
<br />
<?php echo 'timer: '.$time.'<P>' ?>
<br /> 
<?php echo $sestotal; ?>  session total USD to be sent
<br />
<?php echo $total; ?>   Total USD in device
<br />
<?php echo $BTCpaid; ?>   Total BTC paid out by device (in USD)
<br />
<h1 align="center">The Bitcoin ATM</h1>
<div align="center">Model #: <?php echo $model; ?>
  
  </br>Cash tenderd</p>
</div>
<p align="center"><span class="add-on">$</span>
  <input class="span1" id="appendedPrependedInput" value="<?php echo $sestotal; ?>" type="text">
  </span>
  <span class="add-on">.00</span>
<p align="center">
  <?php echo $txout; ?>
  per bitcoin
  <?php echo $lastprice; ?> USD = <?php $txout ?> BTC
<p align="center">Address to Withdrawl To <?php echo $address; ?>
  </h2>
<h3>last Recipt</h3>
<?php echo $recipt; ?>
      
      
      </div></div></div></div></div></body>
  <!--        END GUI ATM                                      -->
