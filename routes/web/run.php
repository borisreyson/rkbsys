<?php

Route::get("/cron/job/interval/time",[
					"uses" => "monitorController@intervalTime",
					"alias"=> "monitor.intervalTime"
				]);
        
