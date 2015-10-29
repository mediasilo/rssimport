<?php

	class ApiClass {

		function getUserInfo($username,$password,$hostname){

			$apiurl = "https://api.mediasilo.com/v3/me/";

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$apiurl);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
			curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
			curl_setopt($ch, CURLOPT_HTTPHEADER, Array("MediaSiloHostContext: " . $hostname));
			$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
			$result=curl_exec($ch);
			curl_close ($ch);
			
			return $result;
		}

		function getUserProjects(){
			
			$user = json_decode($_COOKIE['mediasilo']);
			$apiurl = "https://api.mediasilo.com/v3/projects/";

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$apiurl);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
			curl_setopt($ch, CURLOPT_USERPWD, $user->username . ":" . $user->password);
			curl_setopt($ch, CURLOPT_HTTPHEADER, Array("MediaSiloHostContext: " . $user->hostname));
			$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
			$result=curl_exec($ch);
			curl_close ($ch);
			return $result;
		}

		/**
		 * Creates a new asset against the
		 * @param  string $title       [description]
		 * @param  array $metadata    [description]
		 * @param  string $url         [description]
		 * @param  array $credentials [description]
		 * @return [type]              [description]
		 */
		function createAsset($payload){

			$title = str_replace("'", "", $payload->title);
			$url = $payload->url;

			if(isset($payload->metadata)){
				$metadata = $payload->metadata;
			}
			$targetproject = $payload->targetproject;

			$fields = array(
				'projectId' => urlencode($targetproject),
				'title' => $title,
				'sourceUrl' => $url
			);

			$user = json_decode($_COOKIE['mediasilo']);
			$apiurl = "https://api.mediasilo.com/v3/assets/";

			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$apiurl);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
			curl_setopt($ch, CURLOPT_USERPWD, $user->username . ":" . $user->password);
			curl_setopt($ch, CURLOPT_HTTPHEADER, Array("MediaSiloHostContext: " . $user->hostname, "Content-Type: application/json"));
			curl_setopt($ch, CURLOPT_POST, 1 );
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
			$result=curl_exec($ch);
			curl_close ($ch);

			if(isset($payload->metadata)){
				$id = json_decode($result)->id;
				$this->createMetadata($id,$payload->metadata);
			}
			if($status_code == 200){
				return true;
			} else {
				return false;
			}

		}


		function createMetadata($assetid,$payload){

			$user = json_decode($_COOKIE['mediasilo']);
			$apiurl = "https://api.mediasilo.com/v3/assets/".$assetid."/metadata/";

			$data = array(
				array('key' => 'role','value' => $payload[0]->role ),
				array('key' => 'content','value' => $payload[0]->content )
			);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$apiurl);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, $user->username . ":" . $user->password);
			curl_setopt($ch, CURLOPT_HTTPHEADER, Array("MediaSiloHostContext: " . $user->hostname, "Content-Type: application/json"));
			curl_setopt($ch, CURLOPT_POST, 1 );
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$result=curl_exec($ch);
			curl_close ($ch);
			if($status_code == 200){
				return true;
			} else {
				return false;
			}

		}

		/**
		 * Calls the Project Create endpoint to create a new project
		 * @param  [object] $payload [description]
		 */
		function createProject($payload){

			$fields = array(
				'name' => $payload->projectname,
				'description' => $payload->description
			);
			
			$user = json_decode($_COOKIE['mediasilo']);
			$apiurl = "https://api.mediasilo.com/v3/projects/";
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$apiurl);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
			curl_setopt($ch, CURLOPT_USERPWD, $user->username . ":" . $user->password);
			curl_setopt($ch, CURLOPT_HTTPHEADER, Array("MediaSiloHostContext: " . $user->hostname, "Content-Type: application/json"));
			curl_setopt($ch, CURLOPT_POST, 1 );
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
			$result=curl_exec($ch);
			curl_close ($ch);
			return $result;	
			
		}

		

	}

?>