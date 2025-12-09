<?
	$client = new SoapClient('https://store.toddlekan5.vm-host.net/magento/api/soap?wsdl');
     
    // If somestuff requires api authentification,
    // then get a session token
    $session = $client->login('plugin', 'ksed4mS#');
     
	print "session:";
	print_r($session);
	/*
    $result = $client->call($session, 'somestuff.method');
    $result = $client->call($session, 'somestuff.method', 'arg1');
    $result = $client->call($session, 'somestuff.method', array('arg1', 'arg2', 'arg3'));
    $result = $client->multiCall($session, array(
         array('somestuff.method'),
         array('somestuff.method', 'arg1'),
         array('somestuff.method', array('arg1', 'arg2'))
    ))*/
     
     
    // If you don't need the session anymore
    $client->endSession($session);