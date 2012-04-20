        uuid = argv[1];
	dialnum1 = argv[2];
	dialnum2 = argv[3];
	greeting_snd = "/usr/local/freeswitch/sounds/"..argv[4];

	max_retriesl1 = 5;
	max_retriesl2 = 3;
	connected = false;
	timeout = 45;

	freeswitch.consoleLog("notice", "*********** STARTING Call ***********\n");
	freeswitch.consoleLog("notice", "*********** DIALING "..dialnum1.." ***********\n");

	originate_base1 = "{ignore_early_media=true,originate_timeout=90,hangup_after_bridge=true,origination_uuid="..uuid..",origination_caller_id_number=0970123456,leg=1}";
	originate_str1 = originate_base1.."sofia/external/"..dialnum1.."@sip.tel.nnx.com";

	session1 = null;
	retries = 0;
	ostr = "";
	repeat  
		retries = retries + 1;
	        freeswitch.consoleLog("notice", "*********** Dialing Leg1: " .. originate_str1 .. " - Try: "..retries.." ***********\n");
	        session1 = freeswitch.Session(originate_str1);
	        local hcause = session1:hangupCause();
	        freeswitch.consoleLog("notice", "*********** Leg1: " .. hcause .. " - Try: "..retries.." ***********\n");
	until not ((hcause == 'NO_ROUTE_DESTINATION' or hcause == 'RECOVERY_ON_TIMER_EXPIRE' or hcause == 'INCOMPATIBLE_DESTINATION' or hcause == 'CALL_REJECTED' or hcause == 'NORMAL_TEMPORARY_FAILURE') and (retries < max_retriesl1))

	if (session1:ready()) then
	        -- log to the console
	        freeswitch.consoleLog("notice", "*********** Leg1 ("..ostr..") CONNECTED! ***********\n");

	        -- Play greeting message
	        -- if (not greeting_snd == "") then
	                freeswitch.consoleLog("notice", "*********** Playing greeting sound: "..greeting_snd.." ***********\n");
			digits = session1:playAndGetDigits ( 1, 1, 1, "5000", "#", greeting_snd, "", "\\d")
			if (digits ~= "1") then
			   freeswitch.consoleLog("notice", "*********** Leg1 NO 1 received, :"..digits..": cancelling ***********\n");
			   session1:hangup();
			   return;
			end
	        -- end

	        originate_base2 = "{ignore_early_media=true,originate_timeout=90,hangup_after_bridge=true,origination_uuid="..uuid..",origination_caller_id_number="..dialnum1..",leg=2,origination_caller_id_name=anonymous,sip_h_Privacy=id,privacy=yes}";
		originate_str2 = originate_base2.."sofia/external/"..dialnum2.."@sip.tel.nnx.com";

	        -- Set recording: uncomment these two lines if you'd like to record the call in stereo (one leg on each channel)
	        session1:setVariable("RECORD_STEREO", "true");
	        session1:execute("record_session", "/tmp/"..uuid..".wav");

	        -- Set ringback
	        session1:setVariable("ringback", "%(2000,4000,440,480)");

	        retries = 0;
	        session2 = null;
	        repeat  
	                -- Create session2
	                retries = retries + 1;
	                freeswitch.consoleLog("notice", "*********** Dialing: " .. originate_str2 .. " Try: "..retries.." ***********\n");
	                session2 = freeswitch.Session(originate_str2, session1);
	                local hcause = session2:hangupCause();
	                freeswitch.consoleLog("notice", "*********** Leg2: " .. hcause .. " Try: " .. retries .. " ***********\n");
	        until not ((hcause == 'NO_ROUTE_DESTINATION' or hcause == 'RECOVERY_ON_TIMER_EXPIRE' or hcause == 'INCOMPATIBLE_DESTINATION' or hcause == 'CALL_REJECTED' or hcause == 'NORMAL_TEMPORARY_FAILURE') and (retries < max_retriesl2))

	        if (session2:ready()) then
	                freeswitch.consoleLog("notice", "*********** Leg2 (".. originate_str2 ..") CONNECTED! ***********\n");
	                freeswitch.bridge(session1, session2);

	                -- Hangup session2 if session1 is over
	                if (session2:ready()) then session2:hangup(); end
	        end
	        -- hangup when done
	        if (session1:ready()) then session1:hangup(); end
	end

