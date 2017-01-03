<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<style>
#progress {
	width:200px;
	border:1px solid black;
	background-color:#AAA;
	margin:5px;
	height:20px;
	text-align:center;
	color:black;
}
#progress_done {
	background-color:blue;
	width:0%;
	height:100%;
	float:left;
	color:white;
}
</style>
<div id='status'></div>
<div id='substatus'></div>
<div id='progress'><div id='progress_done'></div></div>
<script>
function download(size = 1024,cb)
{

	$.ajax({
	  xhr: function()
	  {
	    var xhr = new window.XMLHttpRequest();
	    //Upload progress
	    xhr.upload.addEventListener("progress", function(evt){
	      if (evt.lengthComputable) {
	        var percentComplete = evt.loaded / evt.total;
	        //Do something with upload progress
	      }
	    }, false);
	    //Download progress
	    xhr.addEventListener("progress", function(evt){
	      if (evt.lengthComputable) {
	        var percentComplete = evt.loaded / evt.total;
	        var percentStr=(percentComplete*100).toFixed(0)+"%";
	        $("#progress_done").css("width",percentStr ).text(percentStr);
	        //Do something with download progress
	      }
	    }, false);
	    return xhr;
	  },
	  type: 'POST',
	  url: "./data.php?kb="+size,
	  data: {},
	  success: function(data){
	  	cb(data);
	  	// console.log("all done");
	    //Do something success-ish
	  }
	});
}
var SpeedDetector = {
	size:64,
	time:0,
	download_speed:undefined,
	upload_speed:undefined,
	download:function()
	{
		this.size*=2;
		this.time = (new Date()).getTime();
		var sizeStr;
		if (this.size>1024)
			sizeStr=this.size/1024 + "MB";
		else
			sizeStr=this.size+"KB";
		$("#substatus").text("Downloading "+sizeStr+" of data...");
		download(this.size,this.download_done.bind(this));
	},
	download_done:function(data)
	{
		var t=(new Date()).getTime();
		t-=this.time;
		// console.log(data.length/(this.size*1024));
		if (t<5*1000 && data.length/(this.size*1024)>.5)
			this.download();
		else
			this.callback((this.size/t));

	},
	test_download:function()
	{
		$("#status").text("Measuring your download speed, please wait...");
		this.callback=this.test_download_done;
		this.download();
	},
	test_download_done:function(speed_in_mb)
	{
		$("#status").text("Your download speed is "+speed_in_mb.toFixed(3)+" MB/s");
		$("#substatus").text("");
		this.download_speed=speed_in_mb*1024*1024;
	}
}
SpeedDetector.test_download();
</script>
<script>
/*
//JUST AN EXAMPLE, PLEASE USE YOUR OWN PICTURE!
var imageAddr = "data/data.php"; 
var downloadSize = 1024*1024*5; //bytes

function ShowProgressMessage(msg) {
    if (console) {
        if (typeof msg == "string") {
            console.log(msg);
        } else {
            for (var i = 0; i < msg.length; i++) {
                console.log(msg[i]);
            }
        }
    }
    
    var oProgress = document.getElementById("progress");
    if (oProgress) {
        var actualHTML = (typeof msg == "string") ? msg : msg.join("<br />");
        oProgress.innerHTML = actualHTML;
    }
}

function InitiateSpeedDetection() {
    ShowProgressMessage("Loading the image, please wait...");
    window.setTimeout(MeasureConnectionSpeed, 1);
};    

if (window.addEventListener) {
    window.addEventListener('load', InitiateSpeedDetection, false);
} else if (window.attachEvent) {
    window.attachEvent('onload', InitiateSpeedDetection);
}

function MeasureConnectionSpeed() {
    var startTime, endTime;
    var download = new Image();
    download.onload = function () {
        endTime = (new Date()).getTime();
        showResults();
    }
    
    download.onerror = function (err, msg) {
        endTime = (new Date()).getTime();
        showResults();
        ShowProgressMessage("Invalid image, or error downloading");
    }
    
    startTime = (new Date()).getTime();
    var cacheBuster = "?nnn=" + startTime;
    download.src = imageAddr + cacheBuster;
    
    function showResults() {
        var duration = (endTime - startTime) / 1000;
        var bitsLoaded = downloadSize * 8;
        var speedBps = (bitsLoaded / duration).toFixed(2);
        var speedKbps = (speedBps / 1024).toFixed(2);
        var speedMbps = (speedKbps / 1024).toFixed(2);
        ShowProgressMessage([
        	"Duraation:",
        	duration + " seconds",
            "Your connection speed is:", 
            speedBps + " bps", 
            speedKbps + " kbps", 
            speedMbps + " Mbps"
        ]);
    }
}
*/
</script>