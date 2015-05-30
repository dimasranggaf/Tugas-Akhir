<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
    <title>WebRTC (Web Real Time Communication)</title>
    <?php include 'header.php'; ?>
  	<!--script type="text/javascript" src="http://cdn.peerjs.com/0.3/peer.js"></script-->
  	<script>

    // Compatibility shim
    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;

    // PeerJS object
//    var peer = new Peer('<?php echo $username; ?>', {host: '10.151.36.151', port: 9000});
	var peer = new Peer({host: '10.151.36.151', port: 9000});
//    var peer = new Peer({ key: 'lwjd5qra8257b9', debug: 3, config: {'iceServers': [
//      { url: 'stun:stun.l.google.com:19302' } // Pass in optional STUN and TURN server for maximum network compatibility
//    ]}});

    peer.on('open', function(){
     console.log(peer.id);
      <?php

      if($call_to_id!='')
      {
        echo "$('#online').hide();
	      $('#step1').show();";
      }
	?>
    });

    // Receiving a call
    peer.on('call', function(call){
      // Answer the call automatically (instead of prompting user) for demo purposes
      call.answer(window.localStream);
      step3(call);
    });

    peer.on('error', function(err){
      alert(err.message);
      // Return to step 2 if error occurs
      step2();
    });

    // Click handlers setup
    $(function(){
      $('#make-call').click(function(){
        // Initiate a call!
	<?php

	if($call_to_id!='')
	{
        	echo "var call = peer.call('".$call_to_id."', window.localStream);";
	}
	?>
        step3(call);
      });

      $('#end-call').click(function(){
        window.existingCall.close();
        step2();
      });

      // Retry if getUserMedia fails
      $('#step1-retry').click(function(){
        $('#step1-error').hide();
        step1();
      });

      // Get things started
      step1();
    });

    function step1 () {
      // Get audio/video stream
     $('#step1').hide();
     $('#bot-tab').hide();
      navigator.getUserMedia({audio: true, video: {
        mandatory: {
            minWidth: 640,
            minHeight: 480,
            /*Added by Chad*/
            maxWidth: 640,
            maxHeight: 480
        }
    }}, function(stream){
        // Set your video displays
        $('#my-video').prop('src', URL.createObjectURL(stream));

        window.localStream = stream;
      }, function(){ $('#step1-error').show(); });
    }

    function step2 () {
      $('#right-tab').show();
      $('#bot-tab').hide();
    }

    function step3 (call) {
      // Hang up on an existing call if present
      if (window.existingCall) {
        window.existingCall.close();
      }

      // Wait for stream on the call, then set peer video display
      call.on('stream', function(stream){
        $('#their-video').prop('src', URL.createObjectURL(stream));
		getStats(call);
      });

      // UI stuff
      window.existingCall = call;
      $('#their-id').text(call.peer);
      call.on('close', step2);
      $('#right-tab').hide();
      $('#bot-tab').show();
    }

    function showUser()
    {
    if (window.XMLHttpRequest)
      {// code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp = new XMLHttpRequest();
      }
    else
      {// code for IE6, IE5
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
    xmlhttp.onreadystatechange=function()
      {
      if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
          document.getElementById("list-user").innerHTML = "";
          document.getElementById("list-user").innerHTML = xmlhttp.responseText;
        }
      }
    xmlhttp.open("GET","<?php echo base_url();?>call/list_user/",true);
    xmlhttp.send();
    }

    function show()
    {
        setInterval(function (){showUser()}, 5000);
    }
	
	function getStats(peer) {
  _getStats(peer, function (results) {
    var result = {
      audio: {},
      video: {},
      results: results
    };
    var stat = []
    for (var i = 0; i < results.length; ++i) {
      var res = results[i];
     /* if(res.type == 'ssrc'){
        if (!window.prevBytesSent) 
          window.prevBytesSent = res.bytesSent;

        var bytes = res.bytesSent - window.prevBytesSent;
        window.prevBytesSent = res.bytesSent;

        var kilobytes = bytes / 1024;
        stat["bandwidth"] = kilobytes.toFixed(1);
        stat["rtt"] = res.googRtt;
        stat["frate"] = res.googFrameRateSent;
        stat["jitterRecv"] = res.googJitterReceived;
        console.log(stat);
      }*/

      if (res.googCodecName == 'opus') {
        if (!window.prevBytesSent) 
          window.prevBytesSent = res.bytesSent;

        var bytes = res.bytesSent - window.prevBytesSent;
        window.prevBytesSent = res.bytesSent;

        var kilobytes = bytes / 1024;
        result.audio = merge(result.audio, {
                            availableBandwidth: kilobytes.toFixed(1),
                            inputLevel: res.audioInputLevel,
                            packetsLost: res.packetsLost,
                            rtt: res.googRtt,
                            packetsSent: res.packetsSent,
                            bytesSent: res.bytesSent
                        });
      }

      if (i == 15 || i == 12){
        result.video = merge(result.video, {
          googCurrentDelayMs: res.googCurrentDelayMs,
          googJitterBufferMs: res.googJitterBufferMs,
          googFrameRateReceived: res.googFrameRateReceived
        })
      }

      if (res.googCodecName == 'VP8') {
                        if (!window.prevBytesSent)
                            window.prevBytesSent = res.bytesSent;

                        var bytes = res.bytesSent - window.prevBytesSent;
                        window.prevBytesSent = res.bytesSent;

                        var kilobytes = bytes / 1024;

                        result.video = merge(result.video, {
                            availableBandwidth: kilobytes.toFixed(1),
                            googFrameHeightInput: res.googFrameHeightInput,
                            googFrameWidthInput: res.googFrameWidthInput,
                            googCaptureQueueDelayMsPerS: res.googCaptureQueueDelayMsPerS,
                            rtt: res.googRtt,
                            packetsLost: res.packetsLost,
                            packetsSent: res.packetsSent,
                            googEncodeUsagePercent: res.googEncodeUsagePercent,
                            googCpuLimitedResolution: res.googCpuLimitedResolution,
                            googNacksReceived: res.googNacksReceived,
                            googFrameRateInput: res.googFrameRateInput,
                            googPlisReceived: res.googPlisReceived,
                            googViewLimitedResolution: res.googViewLimitedResolution,
                            googCaptureJitterMs: res.googCaptureJitterMs,
                            googAvgEncodeMs: res.googAvgEncodeMs,
                            googFrameHeightSent: res.googFrameHeightSent,
                            googFrameRateSent: res.googFrameRateSent,
                            googBandwidthLimitedResolution: res.googBandwidthLimitedResolution,
                            googFrameWidthSent: res.googFrameWidthSent,
                            googFirsReceived: res.googFirsReceived,
                            bytesSent: res.bytesSent
                        });
                    }

                    if (res.type == 'VideoBwe') {
                        result.video.bandwidth = {
                            googActualEncBitrate: res.googActualEncBitrate,
                            googAvailableSendBandwidth: res.googAvailableSendBandwidth,
                            googAvailableReceiveBandwidth: res.googAvailableReceiveBandwidth,
                            googRetransmitBitrate: res.googRetransmitBitrate,
                            googTargetEncBitrate: res.googTargetEncBitrate,
                            googBucketDelay: res.googBucketDelay,
                            googTransmitBitrate: res.googTransmitBitrate
                        };
                    }
    }
    console.log(result);
    setTimeout(function () {
      getStats(peer);
      }, 10000);
    });
}

    // a wrapper around getStats which hides the differences (where possible)
    // following code-snippet is taken from somewhere on the github
function _getStats(peer, cb) {
  if (!!navigator.mozGetUserMedia) {
    peer.getStats(
      function (res) {
        var items = [];
        res.forEach(function (result) {
          items.push(result);
        });
        cb(items);
      },
    cb
    );
  } else {
    peer.getStats(function (res) {
      var items = [];
      res.result().forEach(function (result) {
        var item = {};
        result.names().forEach(function (name) {
          item[name] = result.stat(name);
        });
        item.id = result.id;
        item.type = result.type;
        item.timestamp = result.timestamp;
        items.push(item);
      });
    cb(items);
    });
  }
}

function merge(mergein, mergeto) {
        if (!mergein) mergein = {};
        if (!mergeto) return mergein;

        for (var item in mergeto) {
            mergein[item] = mergeto[item];
        }
        return mergein;
}

/*function fuzzy(result) {
  if(result.video.rtt <= 1000){

  }
  else(result.video.rtt > 1000 && result.video.rtt <5000){

  }
}*/

  </script>
</head>
<body onload="showUser(); show();">
	<?php include 'navbar.php'; ?>
  <div class="container">
    <div>
      <table class="table table-bordered" width="100%">
        <tr>
          <td align="center">
            <video id="their-video" autoplay></video>
          </td>
          <td id="right-tab" rowspan="2">
          <div id="online" class="">
             <h2>List Online User</h2>
              <div class="bs-component">
               <ul id="list-user" class="nav nav-pills nav-stacked">
               </ul>
             </div>
            </div>
	 <div id="step1" align="center" style="margin-top:20%">
		<p>Anda akan memulai percakapan dengan <strong><?php echo $call_to_id; ?>.</strong></p>
		<p>Tekan tombol di bawah untuk memulai percakapan atau kembali ke halaman sebelumnya.</p>
		<a href="<?php echo base_url(); ?>call/" class="btn btn-default">Kembali</a>&nbsp<button class="btn btn-primary" id="make-call">Mulai</button>
	 </div>
         </td>
        </tr>
        <tr>
          <td align="center">
		<video id="my-video" autoplay width="280px" height="auto"></video>
          </td>
        </tr>
	<tr id="bot-tab">
	   <td align="center">
		<button class="btn btn-primary" id="end-call">Berhenti</button>
	   </td>
	</tr>
    </table>
  </div>
  </div>
</body>
</html>
