<?php
function whichPageMenuDisplay() 
{
	$str = $_SERVER['REQUEST_URI'];
	echo "<br />";
	echo "<div class='bookmarkMenu-add'>";
	
	if(stripos($str,'add_device.php') !== false) 
	{
		echo "<p class='bottom-space'><a class='add-subnet' href='add_subnet.php' onmouseover='addSubnetkdark();' onmouseout='addSubnetdefault();'><img id='subnet-only' class='subnet-img' src='../images/add-subnet-img.png' onmouseover=\"this.src='../images/dark-add-subnet.png'\"\ onmouseout=\"this.src='../images/add-subnet-img.png'\"\><span class='subnet-contain'>Add <strong>Subnet</strong></span></a></p>";
		echo "<a class='add-device-active' href='add_device.php' onmouseover='addDevicedark();' onmouseout='addDevicedefault();'><p class='device-active' onmouseover='addDevicedark();' onmouseout='addDevicedefault();'><img id='device-only' class='device-img' src='../images/add-device-img.png' onmouseover=\"this.src='../images/dark-add-device.png'\"\ onmouseout=\"this.src='../images/add-device-img.png'\"\><span class='device-contain-active'>Add <strong>Device</strong></span></p></a>";
	}
	elseif(stripos($str,'add_subnet.php') !== false) 
	{
		echo ("<a class='add-subnet-active' href='add_subnet.php' onmouseover='addSubnetdark();' onmouseout='addSubnetdefault();'><p class='subnet-active'><img id='subnet-only' class='subnet-img' src='../images/add-subnet-img.png' onmouseover=\"this.src='../images/dark-add-subnet.png'\"\ onmouseout=\"this.src='../images/add-subnet-img.png'\"\><span class='subnet-contain-active'>Add <strong>Subnet</strong></span></p></a>");
		echo "<p><a class='add-device' href='add_device.php' onmouseover='addDevicedark();' onmouseout='addDevicedefault();'><img id='device-only' class='device-img' src='../images/add-device-img.png' onmouseover=\"this.src='../images/dark-add-device.png'\"\ onmouseout=\"this.src='../images/add-device-img.png'\"\><span class='device-contain'>Add <strong>Device</strong></span></a></p>";
	}
	echo ("</div><br>");
	return $page;
}

?>

