<?php
// Pull in the NuSOAP code
require_once('nusoap.php');
// Create the server instance
$server = new soap_server();
// Initialize WSDL support
$server->configureWSDL('servermhsw', 'urn:servermhsw');
// Register the method to expose
$server -> register("inputmhs",					//1.nama metode
					  array('nim'=>'xsd:string', 'nama'=>'xsd:string', 'prodi'=>'xsd:string'),//2.input
					  array('return'=>'xsd:string'),//3.output
						'urn:servermhsw',                    // namespace
						'urn:servermhsw#inputmhs',                // soapaction
					  'rpc',						//6.style
					  'encoded',					//7.use
					  'Untuk menyimpan data mahasiswa baru'  //8.ket  	
	);
$server->register('ambilnim',                // method name
						array('nim' => 'xsd:string'),        // input parameters
						array('return' => 'xsd:string'),    // output parameters
						'urn:servermhsw',                    // namespace
						'urn:servermhsw#ambilnim',                // soapaction
						'rpc',                                // style
						'encoded',                            // use
						'Says hello to the caller'            // documentation
);
// Define the method as a PHP function


function inputmhs($nim, $nama, $prodi) {
			
		$cn = mysql_connect("localhost", "root", "");
		mysql_select_db("akademik",$cn);
		mysql_query("insert into mahasiswa(nim, nama, prodi) values('$nim', '$nama','$prodi')",$cn);
		if (mysql_affected_rows($cn)>0) {
			return "Berhasil";
		} else {
			return "Gagal";
		}
	}

function ambilnim($nim) {
		
		$cn = mysql_connect("localhost", "root", "");
		mysql_select_db("akademik",$cn);
		$hasil = mysql_query("SELECT nim,nama,prodi FROM `mahasiswa` where nim = '$nim'",$cn);
		
		$data = mysql_fetch_row($hasil);
		
		$m = 'nim='.$data[0].' nama='.$data[1].' prodi='.$data[2];
		return 'Hasil query, ' .$m;	 
}


// Use the request to (try to) invoke the service
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>