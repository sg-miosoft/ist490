<?php
function whichPageMenuDisplay($page) 
{
	echo "<aside>";
	if(strcmp($page,"device") == 0) 
	{
		echo "<div class='aside-inactive' onmouseover='addSubnetdark();' onmouseout='addSubnetdefault();' onclick=\"location.href='add.php?type=subnet';\">
			<div class='aside-image'>
				<img id='subnet-only' class='subnet-img' src='../images/add-subnet-img.png' onmouseover=\"this.src='../images/dark-add-subnet.png'\"\ onmouseout=\"this.src='../images/add-subnet-img.png'\"\>
			</div>
			<span>Add <strong>Subnet</strong></span>
		</div>";

		echo "<div class='aside-active' onmouseover='addDevicedark();' onmouseout='addDevicedefault();'>
			<div class='aside-image'>
				<img id='device-only' class='device-img' src='../images/add-device-img.png' onmouseover=\"this.src='../images/dark-add-device.png'\"\ onmouseout=\"this.src='../images/add-device-img.png'\"\>
			</div>
			<span>Add <strong>Device</strong></span>
		</div>";
	}
	elseif(strcmp($page,"subnet") == 0) 
	{
		echo "<div class='aside-active' onmouseover='addSubnetdark();' onmouseout='addSubnetdefault();'>
			<div class='aside-image'>
				<img id='subnet-only' class='subnet-img' src='../images/add-subnet-img.png' onmouseover=\"this.src='../images/dark-add-subnet.png'\"\ onmouseout=\"this.src='../images/add-subnet-img.png'\"\>
			</div>
			<span>Add <strong>Subnet</strong></span>
		</div>";
	
		echo "<div class='aside-inactive' onmouseover='addDevicedark();' onmouseout='addDevicedefault();' onclick=\"location.href='add.php?type=device';\">
			<div class='aside-image'>
				<img id='device-only' class='device-img' src='../images/add-device-img.png' onmouseover=\"this.src='../images/dark-add-device.png'\"\ onmouseout=\"this.src='../images/add-device-img.png'\"\>
			</div>
			<span>Add <strong>Device</strong></span>
		</div>";
	}
	else
	{
		echo "<div class='aside-inactive' onmouseover='addSubnetdark();' onmouseout='addSubnetdefault();' onclick=\"location.href='add.php?type=subnet';\">
			<div class='aside-image'>
				<img id='subnet-only' class='subnet-img' src='../images/add-subnet-img.png' onmouseover=\"this.src='../images/dark-add-subnet.png'\"\ onmouseout=\"this.src='../images/add-subnet-img.png'\"\>
			</div>
			<span>Add <strong>Subnet</strong></span>
		</div>";
		echo "<div class='aside-inactive' onmouseover='addDevicedark();' onmouseout='addDevicedefault();' onclick=\"location.href='add.php?type=device';\">
			<div class='aside-image'>
				<img id='device-only' class='device-img' src='../images/add-device-img.png' onmouseover=\"this.src='../images/dark-add-device.png'\"\ onmouseout=\"this.src='../images/add-device-img.png'\"\>
			</div>
			<span>Add <strong>Device</strong></span>
		</div>";
	}
	echo "</aside>";
}

?>
<script>
	function addSubnetdark() {
		document.getElementById("subnet-only").src='../images/dark-add-subnet.png';
	}
	
	function addSubnetdefault() {
		document.getElementById("subnet-only").src='../images/add-subnet-img.png';
	}
	
	function addDevicedark() {
		document.getElementById("device-only").src='../images/dark-add-device.png';
	}
	
	function addDevicedefault() {
		document.getElementById("device-only").src='../images/add-device-img.png';
	}
</script>

