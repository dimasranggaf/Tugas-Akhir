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
    var peer = new Peer('<?php echo $username; ?>', {host: '10.151.36.151', port: 9000});
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
        step2();
      }, function(){ $('#step1-error').show(); });
    }

    function step2 () {
      $('#right-tab').show();
      $('#bot-tab').hide();
    }

    function step3 (call) {
      // Hang up on an existing call if present
//      if (window.existingCall) {
//        window.existingCall.close();
//      }

      // Wait for stream on the call, then set peer video display
      call.on('stream', function(stream){
        $('#their-video').prop('src', URL.createObjectURL(stream));
      });

      // UI stuff
      window.existingCall = call;
      $('#their-id').text(call.peer);
      call.on('close', step2);
      $('#right-tab').hide();
      $('#bot-tab').show();
    }

    function showRoom()
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
          document.getElementById("list-room").innerHTML = "";
          document.getElementById("list-room").innerHTML = xmlhttp.responseText;
        }
      }
    xmlhttp.open("GET","<?php echo base_url();?>room/list_room/",true);
    xmlhttp.send();
    }

    function show()
    {
        setInterval(function (){showRoom()}, 5000);
    }

    function createRoom()
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
          document.getElementById("create").innerHTML = "";
          document.getElementById("create").innerHTML = xmlhttp.responseText;
        }
      }
    xmlhttp.open("GET","<?php echo base_url();?>room/create?inputName="+$('#inputName').val()+"&inputPassword="+$('#inputPassword').val(),true);
    xmlhttp.send();
    }

    function joinRoom(elem)
    {
      console.log(elem.id);
      $('#roomName').val(elem.id);
      $('#headerJoin').text("Join Room "+elem.id);
      $("#formJoin").attr({
            "action" : "<?php echo base_url(); ?>room/join/"+elem.id
        });
    }

  </script>
</head>
<body onload="showRoom(); show();">
	<?php include 'navbar.php'; ?>
  <div class="container">
    <div class="modal fade" id="formRoom" tabindex="-1" role="dialog" aria-labelledby="formRoomLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h4 class="modal-title">Form Create Room</h4>
            </div>
            <div class="modal-body">
                <fieldset>
                  <div class="form-group">
                    <label for="inputName" class="col-lg-3 control-label">Room Name</label>
                    <div class="col-lg-10">
                      <input type="text" class="form-control" id="inputName" name="inputName" placeholder="Room Name">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputPassword" class="col-lg-3 control-label">Password</label>
                    <div class="col-lg-10">
                      <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Password">
                    </div>
                  </div>
                </fieldset>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="createRoom()" data-dismiss="modal">Create</button>
            </div>
          </div>
        </div>
      </div>
    <div class="modal fade" id="joinRoom" tabindex="-1" role="dialog" aria-labelledby="joinLoginLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form id="formJoin" class="form-horizontal" method="POST">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h4 id="headerJoin" class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <fieldset>
                  <div class="form-group">
                    <input type="hidden" id="roomName" name="roomName">
                    <label for="inputPassword" class="col-lg-2 control-label">Password</label>
                    <div class="col-lg-10">
                      <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Password">
                    </div>
                  </div>
                </fieldset>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Join</button>
            </div>
          </form>
          </div>
        </div>
    </div>
    <div id="create">
    </div>
    <div>
      <table class="table table-bordered" width="100%">
        <tr>
          <td align="center">
            <video id="their-video" autoplay></video>
          </td>
          <td id="right-tab" rowspan="2">
          <div id="online">
            <h2>List Available Rooms</h2>
              <div class="bs-component">
               <ul id="list-room" class="nav nav-pills nav-stacked">
               </ul>
		<br/>
		<button class="btn btn-primary" data-toggle="modal" data-target="#formRoom">Create New Room</button>
             </div>
          </div>
	 <div id="step1" align="center" style="margin-top:20%">
		<p>Anda akan memulai percakapan dengan <strong><?php echo $call_to_id; ?>.</strong> sebagai speaker room</p>
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
