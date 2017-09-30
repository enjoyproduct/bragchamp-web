<?php


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include "connect.php";

define("SUCCESS", "success");
define("FAILED", "failed");
define("INVALID_PARAMETER", "Invalid parameter");
define("INVALID_AUTHKEY", "Invalid auth key");
define("EMPTY_AUTHKEY", "Empty auth key");
define("BASE_URL", "http://bragchamp.com");
define("AVATAR_URL", BASE_URL."/uploads/avatars/");
define("UPLOAD_MAX_SIZE", 1048576000);
define("AUTH_SURFIX", "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890");
define("AVATARS", serialize(array(
		"1482818965_malecostume.png",
		"1482818970_male.png",
		"1482818975_female.png",
		"1482818979_matureman.png",
		"1482818983_supportmale.png",
		"1482818987_boy.png",
		"1482818992_supportfemale.png",
		"1482818996_matureman2.png",
		"1482819000_maturewoman.png",
		"1482819004_girl.png"
	)));

$possible_url = array(	
			"login",
			"signup",
			"change_password",
			"postvideo",
			"likevideo",
			"dislikevideo",
			"unlikevideo",
			"viewvideo",
			"comment",
			"challengevideo",
			"like_challenge",
			"dislike_challenge",
			"unlike_challenge",
			"get_populars",
			"get_challenges",
			"get_recents",
			"get_video",
			"get_challenge",
			"get_comment",
			"get_followers",
			"upload_thumbnail",
			"upload_avatar",
			"update_profile",
			"follow",
			"unfollow",
			"approve_friend_request",
			"get_userinfo",
			"report",
			"block",
			"challenge_comment",
			"get_challenge_comment",
			"ignore_notification",
			"get_notification",
			"view_notification",
			"search_people",
			"search_video",
			"send_message",
			"get_message",
			"delete_video",
			"delete_post_comment", 
			"delete_challenge_comment", 
			"delete_message");
			
$value = array();

if (isset($_POST["action"]) && in_array($_POST["action"], $possible_url)) {
  	switch ($_POST["action"]) {
      	case "login":
      		if (isset($_POST["user_name"]) && isset($_POST["password"])) {
				$userName = $_POST["user_name"];
				$password = $_POST["password"];
				$value = login($userName, $password, $conn);
			} else {
				$value["status"] = FAILED;
				$value["message"] = INVALID_PARAMETER;
			}
			break;
		case "signup":
			if (isset($_POST["fname"]) && isset($_POST["lname"]) && isset($_POST["email"]) 
				&& isset($_POST["birthday"]) && isset($_POST["user_name"]) && isset($_POST["password"])) {
				$user["fname"] = $_POST["fname"];
				$user["lname"] = $_POST["lname"];
				$user["email"] = $_POST["email"];
				$user["birthday"] = $_POST["birthday"];
				$user["user_name"] = $_POST["user_name"];
				$user["password"] = $_POST["password"];
				$value = signup($user, $conn);
			} else {
				$value["status"] = FAILED;
				$value["message"] = INVALID_PARAMETER;
			}
			break;
		case "change_password":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["old_password"]) && isset($_POST["new_password"])) {
						$old_password = $_POST["old_password"];
						$new_password = $_POST["new_password"];
						$value = change_password($authkey, $old_password, $new_password, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "postvideo":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["user_id"]) && isset($_POST["ch_title"]) && isset($_POST["ch_count"]) && isset($_POST["ch_mode"]) && isset($_POST["thumbnail_url"])) {
						$user_id = $_POST["user_id"];
						// $ch_title = escape_string($_POST["ch_title"]);
						$ch_title = ($_POST["ch_title"]);
						$ch_count = $_POST["ch_count"];
						$ch_mode = $_POST["ch_mode"];
						$hashtags = "";
						$thumbnail_url = $_POST["thumbnail_url"];
						if (isset($_POST["hashtags"])) {
							$hashtags = escape_string($_POST["hashtags"]);
						}
						$value = postvideo($user_id, $ch_title, $ch_count, $ch_mode, $hashtags, $thumbnail_url, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "likevideo":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["user_id"]) && isset($_POST["post_id"])) {
						$user_id = $_POST["user_id"];
						$post_id = $_POST["post_id"];
						$value = likevideo($user_id, $post_id, 0, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "dislikevideo":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["user_id"]) && isset($_POST["post_id"])) {
						$user_id = $_POST["user_id"];
						$post_id = $_POST["post_id"];
						$value = likevideo($user_id, $post_id, 1, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "unlikevideo":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["user_id"]) && isset($_POST["post_id"])) {
						$user_id = $_POST["user_id"];
						$post_id = $_POST["post_id"];
						$value = unlikevideo($user_id, $post_id, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "viewvideo":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["user_id"]) && isset($_POST["post_id"])) {
						$user_id = $_POST["user_id"];
						$post_id = $_POST["post_id"];
						$value = viewvideo($user_id, $post_id, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "like_challenge":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["user_id"]) && isset($_POST["ch_id"])) {
						$user_id = $_POST["user_id"];
						$ch_id = $_POST["ch_id"];
						$value = likeChallenge($user_id, $ch_id, 0, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "dislike_challenge":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["user_id"]) && isset($_POST["ch_id"])) {
						$user_id = $_POST["user_id"];
						$ch_id = $_POST["ch_id"];
						$value = likeChallenge($user_id, $ch_id, 1, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "unlike_challenge":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["user_id"]) && isset($_POST["ch_id"])) {
						$user_id = $_POST["user_id"];
						$ch_id = $_POST["ch_id"];
						$value = unlikeChallenge($user_id, $ch_id, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "comment":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["user_id"]) && isset($_POST["post_id"]) && isset($_POST["content"])) {
						$user_id = $_POST["user_id"];
						$post_id = $_POST["post_id"];
						// $content = escape_string($_POST["content"]);
						$content = ($_POST["content"]);
						$value = comment($user_id, $post_id, $content, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "challengevideo":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["user_id"]) && isset($_POST["post_id"]) && isset($_POST["thumbnail_url"])) {
						$user_id = $_POST["user_id"];
						$post_id = $_POST["post_id"];
						$thumbnail_url = $_POST["thumbnail_url"];
						$value = challengevideo($user_id, $post_id, $thumbnail_url, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "get_populars":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					$value = get_populars($conn);
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "get_challenges":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					$value = get_challenges($conn);
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "get_recents":
			$authkey = $_POST["authkey"];
			$offset  = $_POST["offset"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					$value = get_recents($conn, $offset);
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "get_video":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["post_id"]) && isset($_POST["user_id"])) {
						$post_id = $_POST["post_id"];
						$user_id = $_POST["user_id"];
						$value = get_video($user_id, $post_id, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "get_challenge":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["post_id"]) && isset($_POST["user_id"])) {
						$post_id = $_POST["post_id"];
						$user_id = $_POST["user_id"];
						$value = get_challenge($post_id, $user_id, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "get_comment":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["post_id"])) {
						$post_id = $_POST["post_id"];
						$value = get_comment($post_id, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "get_followers":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["user_id"]) && isset($_POST["follow_type"])) {
						$user_id = $_POST["user_id"];
						$follow_type = $_POST["follow_type"];
						$value = get_followers($user_id, $follow_type, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "upload_thumbnail":
			$value = upload_thumbnail($conn);
			break;
		case "upload_avatar":
			$value = upload_avatar($conn);
			break;
		case "update_profile":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["bio"]) && isset($_POST["user_name"])) {
						$bio = $_POST["bio"];
						$user_name = $_POST["user_name"];
						$new_avatar = $_POST["new_avatar"];
						$thumbnail = "";
						$thumbnail_url = "";
						if (isset($_POST["avatar_name"])) {
							$avatar_name = $_POST["avatar_name"];
							$avatar_url = $_POST["avatar_url"];
						}
						$value = update_profile($authkey, $bio, $user_name, $avatar_name, $avatar_url, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			break;
		case "follow":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["following_id"]) && isset($_POST["follower_id"])) {
						$follower_id = $_POST["follower_id"];
						$following_id = $_POST["following_id"];
						$value = follow($follower_id, $following_id, $conn);	
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
					
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "unfollow":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["following_id"]) && isset($_POST["follower_id"])) {
						$follower_id = $_POST["follower_id"];
						$following_id = $_POST["following_id"];
						$value = unfollow($follower_id, $following_id, $conn);	
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
					
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "approve_friend_request":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["notification_id"]) && isset($_POST["follow_id"]) && isset($_POST["state"])) {
						$notification_id = $_POST["notification_id"];
						$follow_id = $_POST["follow_id"];
						$state = $_POST["state"];
						$value = approve_friend_request($notification_id, $follow_id, $state, $conn);	
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
					
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
			break;
		case "get_userinfo":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["user_id"])) {
						$user_id = $_POST["user_id"];
						$my_id = $_POST["my_id"];
						$value = get_userinfo($authkey, $user_id, $my_id, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
					
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "report":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["user_id"]) && isset($_POST["by_userid"]) && isset($_POST["content"])) {
						$user_id = $_POST["user_id"];
						$by_userid = $_POST["by_userid"];
						$content = escape_string($_POST["content"]);
						$value = report($user_id, $by_userid, $content, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "block":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["user_id"]) && isset($_POST["by_userid"]) && isset($_POST["state"])) {
						$user_id = $_POST["user_id"];
						$by_userid = $_POST["by_userid"];
						$state = $_POST["state"];
						$value = block($user_id, $by_userid, $state, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "challenge_comment":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["user_id"]) && isset($_POST["challenge_id"]) && isset($_POST["content"])) {
						$user_id = $_POST["user_id"];
						$challenge_id = $_POST["challenge_id"];
						$content = escape_string($_POST["content"]);
						$value = challenge_comment($user_id, $challenge_id, $content, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "get_challenge_comment":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["challenge_id"])) {
						$challenge_id = $_POST["challenge_id"];
						$value = get_challenge_comment($challenge_id, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "ignore_notification":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["id"])) {
						$id = $_POST["id"];
						$value = ignore_notification($id, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "get_notification":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["type"]) && isset($_POST["user_id"])) {
						$user_id = $_POST["user_id"];
						$type = $_POST["type"];
						$value = get_notification($user_id, $type, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "view_notification":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["id"])) {
						$id = $_POST["id"];
						$value = view_notification($id, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "search_people":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["keyword"])) {
						$keyword = $_POST["keyword"];
						$value = search_people($keyword, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "search_video":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["keyword"])) {
						$keyword = $_POST["keyword"];
						$value = search_video($keyword, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;	
					}
				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			
			break;
		case "send_message":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["sender_id"]) && isset($_POST["receiver_id"])) {
						$sender_id = $_POST["sender_id"];
						$receiver_id = $_POST["receiver_id"];
						$message = $_POST["message"];
						$value = send_message($sender_id, $receiver_id, $message, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;
					}

				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			break;
		case "get_message":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["user1_id"]) && isset($_POST["user2_id"])) {
						$user1_id = $_POST["user1_id"];
						$user2_id = $_POST["user2_id"];

						$value = get_message($user1_id, $user2_id, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;
					}

				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			break;
		case "delete_video":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["user_id"]) && isset($_POST["post_id"])) {
						$user_id = $_POST["user_id"];
						$post_id = $_POST["post_id"];

						$value = delete_video($user_id, $post_id, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;
					}

				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			break;
		case "delete_post_comment":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["user_id"]) && isset($_POST["comment_id"])) {
						$user_id = $_POST["user_id"];
						$comment_id = $_POST["comment_id"];

						$value = delete_post_comment($user_id, $comment_id, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;
					}

				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			break;
		case "delete_challenge_comment":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["user_id"]) && isset($_POST["comment_id"])) {
						$user_id = $_POST["user_id"];
						$comment_id = $_POST["comment_id"];

						$value = delete_challenge_comment($user_id, $comment_id, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;
					}

				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			break;
		case "delete_message":
			$authkey = $_POST["authkey"];
			if (isset($authkey)) {
				if (isValidAuthKey($authkey, $conn)) {
					if (isset($_POST["user_id"]) && isset($_POST["message_id"])) {
						$user_id = $_POST["user_id"];
						$message_id = $_POST["message_id"];

						$value = delete_message($user_id, $message_id, $conn);
					} else {
						$value["status"] = FAILED;
						$value["message"] = INVALID_PARAMETER;
					}

				} else {
					$value["status"] = FAILED;
					$value["message"] = INVALID_AUTHKEY;
				}
			} else {
				$value["status"] = FAILED;
				$value["message"] = EMPTY_AUTHKEY;
			}
			break;
	}
}
function send_message($sender_id, $receiver_id, $message, $conn) {
	$response = array();
	//add message
	$add_message_query = "INSERT INTO tbl_message(sender_id, receiver_id, message) VALUES('$sender_id', '$receiver_id', '$message')";
	$result = $conn->query($add_message_query);
	if ($result) {
		//get sender info
		$user_query = "SELECT * FROM user WHERE id=$sender_id";
		$user_result = $conn->query($user_query);
		if (!$user_result) {
			$response["status"] = FAILED;
			$response["message"] = "Failed to get user info";
			return $response;
		}
		$userData = $user_result->fetch_assoc();
		unset($userData["authkey"]);
		unset($userData["password"]);
		//add notification
		$notificationContent = array("user" => $userData);
		$content = json_encode($notificationContent);
		//get message id
		$get_message_query = "SELECT * FROM tbl_message ORDER BY id DESC LIMIT 1";
		$messageResult = $conn->query($get_message_query);
		$message = $messageResult->fetch_assoc();
		$message_id = $message['id'];

		$notification_query = "INSERT INTO notification(receiver_id, content, type, object_id, post_id) VALUES ('$receiver_id', '$content', 6, '$message_id', '$sender_id')";
		$notification_result = $conn->query($notification_query);
		if (!$notification_result) {
			$response["status"] = FAILED;
			$response["message"] = "Failed to insert notification";
			return $response;
		}
		$response["status"] = SUCCESS;
		$response["message"] = SUCCESS;

	} else {
		$response["status"] = FAILED;
		$response["message"] = "Failed to insert message";

	}
	
	return $response;

}
function get_message($sender_id, $receiver_id, $conn) {
	$query = "SELECT * FROM tbl_message WHERE (sender_id='$receiver_id' AND receiver_id='$sender_id') OR (sender_id='$sender_id' AND receiver_id='$receiver_id')";
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		$response["status"] = SUCCESS;
		$response["message"] = $result->num_rows."rows";
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		$response["data"] = $data;
	} else {
		$response["status"] = SUCCESS;
		$response["message"] = "Empty row";
		$response["data"] = array();
	}

	return $response;
}
function delete_message($user_id, $message_id, $conn) {
	//delete message
	$delete_message_query = "DELETE FROM tbl_message WHERE id=$message_id AND sender_id=$user_id";
	$result1 = $conn->query($delete_message_query);
	if (!$result1) {
		$response["status"] = FAILED;
		$response["message"] = "Failed to delete message";
		return $response;
	}
	//delete notification
	$delete_notification_query = "DELETE  FROM notification WHERE type=6 AND object_id=$message_id";
	$result2 = $conn->query($delete_notification_query);
	if (!$result2) {
		$response["status"] = FAILED;
		$response["message"] = "Failed to delete notification";
		return $response;
	}
	$response["status"] = SUCCESS;
		$response["message"] = "Deleted successfully";
		return $response;
	
}
function delete_post_comment($user_id, $post_comment_id, $conn) {
	//delete post comment
	$delete_post_comment_query = "DELETE FROM post_comment WHERE id=$post_comment_id  AND user_id=$user_id";
	$result1 = $conn->query($delete_post_comment_query);
	if (!$result1) {
		$response["status"] = FAILED;
		$response["message"] = "Failed to delete comment";
		return $response;
	}
	//delete notification
	$delete_notification_query = "DELETE FROM notification WHERE type=2 AND object_id=$post_comment_id";
	$result2 = $conn->query($delete_notification_query);
	if (!$result2) {
		$response["status"] = FAILED;
		$response["message"] = "Failed to delete notification";
		return $response;
	}
	$response["status"] = SUCCESS;
	$response["message"] = "Deleted successfully";
	return $response;
}
function delete_challenge_comment($user_id, $challenge_comment_id, $conn) {
	//delete challenge comment
	$delete_challenge_comment_query = "DELETE FROM challenge_comment WHERE id=$challenge_comment_id  AND user_id=$user_id";
	$result1 = $conn->query($delete_challenge_comment_query);
	if (!$result1) {
		$response["status"] = FAILED;
		$response["message"] = "Failed to delete comment";
		return $response;
	}
	//delete notification
	$delete_notification_query = "DELETE FROM notification WHERE type=3 AND object_id=$challenge_comment_id";
	$result2 = $conn->query($delete_notification_query);
	if (!$result2) {
		$response["status"] = FAILED;
		$response["message"] = "Failed to delete notification";
		return $response;
	}
	$response["status"] = SUCCESS;
	$response["message"] = "Deleted successfully";
	return $response;
}
function delete_video($user_id, $post_id, $conn) {
	$get_challenge_query = "SELECT * FROM challenge WHERE post_id=$post_id AND user_id=$user_id";
	$result = $conn->query($get_challenge_query);
	if ($result->num_rows > 0) {
		$rows = $result->fetch_assoc();
		while ($row = $rows) {
			$challenage[] = $row;
			$ch_id = $challenge['id'];
			//delete challenge like
			$delete_challenge_like_query = "DELETE FROM challenge_like WHERE ch_id=$ch_id AND user_id=$user_id";
			$result1 = $conn->query($delete_challenge_like_query);
			if (!$result1) {
				$response["status"] = FAILED;
				$response["message"] = "Failed to delete change like";
				return $response;
			}
			//delete challenge comment
			$delete_challenge_comment_query = "DELETE FROM challenge_comment WHERE challenge_id=$ch_id AND user_id=$user_id";
			$result1 = $conn->query($delete_challenge_comment_query);
			if (!$result1) {
				$response["status"] = FAILED;
				$response["message"] = "Failed to delete callenge comment";
				return $response;
			}
			//delete challenge
			$delete_challenge_query = "DELETE FROM challenge WHERE id=$ch_id";
			$result1 = $conn->query($delete_challenge_query);
			if (!$result1) {
				$response["status"] = FAILED;
				$response["message"] = "Failed to delete challenge";
				return $response;
			}
			//delete notification
			$delete_notification_query = "DELETE FROM notification WHERE (type=3 OR type=5) AND post_id=$ch_id";
			$result2 = $conn->query($delete_notification_query);
			if (!$result2) {
				$response["status"] = FAILED;
				$response["message"] = "Failed to delete notification";
				return $response;
			}
		}
	}

	//delete notification
	$delete_notification_query = "DELETE FROM notification WHERE (type=2 OR type=4) AND post_id=$post_id";
	$result2 = $conn->query($delete_notification_query);
	if (!$result2) {
		$response["status"] = FAILED;
		$response["message"] = "Failed to delete notification";
		return $response;
	}
	//delete post like
	$delete_post_like_query = "DELETE FROM post_like WHERE post_id=$post_id AND user_id=$user_id";
	$result1 = $conn->query($delete_post_like_query);
	if (!$result1) {
		$response["status"] = FAILED;
		$response["message"] = "Failed to delete post like";
		return $response;
	}
	//delete post comment
	$delete_post_comment_query = "DELETE FROM post_comment WHERE post_id=$post_id AND user_id=$user_id";
	$result1 = $conn->query($delete_post_comment_query);
	if (!$result1) {
		$response["status"] = FAILED;
		$response["message"] = "Failed to delete post comment";
		return $response;
	}
	//delete post
	$delete_post_query = "DELETE FROM post WHERE id=$post_id";
	$result1 = $conn->query($delete_post_query);
	if (!$result1) {
		$response["status"] = FAILED;
		$response["message"] = "Failed to delete post";
		return $response;
	}
	$response["status"] = SUCCESS;
	$response["message"] = "Deleted successfully";
	return $response;
}
function login($user_name, $password, $conn) {
	$response = array();
	$password = md5($password.AUTH_SURFIX);
	$user_name = strtolower($user_name);
//	if (!spellCheck($user_name)) {
//		$response["status"] = FAILED;
//		$response["message"] = "User name doesn't must be contained the special character.";
//		return $response;
//	}

	$query = "SELECT * FROM user WHERE user_name='$user_name'";
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		if ($row["password"] == $password) {
			
			$authkey = gen_authkey();
			date_default_timezone_set("UTC");
			$date = date('Y-m-d H:i:s');

			$update = "UPDATE user SET authkey='$authkey', last_logintime='$date' WHERE user_name='$user_name'";
			$conn->query($update);

			unset($row["password"]);
			$row["authkey"] = $authkey;
			// $data = array(
			// 		'user_id' => $row["id"],
			// 		'user_name' => $row["user_name"],
			// 		'fname' => $row["fname"],
			// 		'lname' => $row["lname"],
			// 		'email' => $row["email"],
			// 		'birthday' => $row["birthday"],
			// 		'authkey' => $authkey,
			// 		'avatar_name' => $row["avatar_name"],
			// 		'avatar_url' => $row["avatar_url"],
			// 		'bio' => $row["bio"]
			// 	);

			$response["status"] = SUCCESS;
			$response["message"] = "Login successed";
			$response["data"] = $row;
		} else {
			$response["status"] = FAILED;
			$response["message"] = "Invalid password";
		}
	} else {
		$response["status"] = FAILED;
		$response["message"] = "Invalid User Name.";
	}

	return $response;
}

function emailCheck($email, $conn) {
	$query = "SELECT * FROM user WHERE email='$email'";
	$result = $conn->query($query);
	return $result->num_rows;
}

function signup($user, $conn) {
	$response = array();
	$authkey = gen_authkey();
	$password = md5($user["password"].AUTH_SURFIX);
	$fname = escape_string($user["fname"]);
	$lname = escape_string($user["lname"]);
	$email = strtolower($user["email"]);
	$user_name = strtolower($user["user_name"]);
	$birthday = $user["birthday"];

	if (!spellCheck($user_name)) {
		$response["status"] = FAILED;
		$response["message"] = "User name doesn't must be contained the special character.";
		return $response;
	}

	if (emailCheck($email, $conn) > 0) {
		$response["status"] = FAILED;
		$response["message"] = "This email is already registered by another user.";
		return $response;
	}

	$query = "SELECT * FROM user WHERE user_name='$user_name'";
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		$response["status"] = FAILED;
		$response["message"] = "This username is already existing.";
	} else {
		date_default_timezone_set("UTC");
		$date = date('Y-m-d H:i:s');
		$avatars = unserialize(AVATARS);
		$avatar = $avatars[rand(0,9)];
		$avatarURL = "";
		if ($avatar != "") {
			$avatarURL = AVATAR_URL.$avatar;
		}
		$query = "INSERT INTO user(fname, lname, email, birthday, user_name, password, authkey, avatar_name, avatar_url, last_logintime)
				  VALUES ('$fname', '$lname', '$email', '$birthday', '$user_name', '$password', '$authkey', '$avatar', '$avatarURL', '$date')";
		$state = $conn->query($query);
		if ($state) {
			$data["id"] = $conn->insert_id;
			$data["authkey"] = $authkey;
			$data["avatar_name"] = $avatar;
			$data["avatar_url"] = $avatarURL;
			$response["status"] = SUCCESS;
			$response["message"] = "Signup successed";
			$response["data"] = $data;
		} else {
			$response["status"] = FAILED;
			$response["message"] = "Sign up was failed";
		}
	}

	return $response;
}

function upload_thumbnail($conn) {
	$response = array();
	$targetPath = "../uploads/thumbs/";
	$fileName = $_FILES["file"]["name"];
	$targetPath .= $fileName;
	if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetPath)) {
		$response["status"] = SUCCESS;
		$response["message"] = "Upload was successed";
		$response["thumbnail"] = $fileName;
		$response["thumbnail_url"] = BASE_URL."/uploads/thumbs/".$fileName;
	} else {
		$response["status"] = FAILED;
		$response["message"] = "Upload was failed";
	}
	return $response;
}

function upload_avatar($conn) {
	$response = array();
	$targetPath = "../uploads/avatars/";
	$fileName = $_FILES["file"]["name"];
	$targetPath .= $fileName;
	if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetPath)) {
		$response["status"] = SUCCESS;
		$response["message"] = "Upload was successed";
		$response["avatar_name"] = $fileName;
		$response["avatar_url"] = BASE_URL."/uploads/avatars/".$fileName;
	} else {
		$response["status"] = FAILED;
		$response["message"] = "Upload was failed";
	}
	return $response;
}

function postvideo($user_id, $ch_title, $ch_count, $ch_mode, $hashtags, $thumbnail_url, $conn) {
	$response = array();
	
	$targetPath = "../uploads/videos/";
	$fileName = $_FILES["file"]["name"];
	$response["files"] = $_FILES;
	$targetPath .= $fileName;
	if ($_FILES["file"]["size"] > UPLOAD_MAX_SIZE) {
		$response["status"] = FAILED;
		$response["message"] = "File size is very large.";
		return $response;
	}
	if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetPath)) {
		$video_name = $fileName;
		$video_url = BASE_URL."/uploads/videos/".$video_name;
		$query = "INSERT INTO post(user_id, ch_title, ch_count, ch_mode, hashtags, video_name, video_url, thumbnail_url)
					VALUES ($user_id, '$ch_title', '$ch_count', '$ch_mode', '$hashtags', '$video_name', '$video_url', '$thumbnail_url')";
		$state = $conn->query($query);
		if ($state) {
			$data = array(
					"id" => $conn->insert_id,
					"user_id" => $user_id,
					"ch_title" => $ch_title,
					"ch_count" => $ch_count,
					"ch_mode" => $ch_mode,
					"hashtags" => $hashtags,
					"video_name" => $video_name,
					"video_url" => $video_url,
					"thumbnail_url" => $thumbnail_url
				);
			$response["status"] = SUCCESS;
			$response["message"] = "Upload was successed.";
			// $response["data"] = $data;

			$query = "SELECT * FROM user WHERE id=$user_id";
			$result = $conn->query($query);
			$userData = $result->fetch_assoc();
			
			unset($userData["authkey"]);
			unset($userData["password"]);
			//get post id
			$get_post_query = "SELECT * FROM post ORDER BY id DESC LIMIT 1";
			$postResult = $conn->query($get_post_query);
			$postData = $postResult->fetch_assoc();
			$post_id = $postData['id'];
			//
			$strArr = filter_username($ch_title);
			$userStr = "";
			if (count($strArr) > 0) {
				foreach ($strArr as $value) {
					$userStr .= "'" . $value . "',";	
				}
				$userStr = substr($userStr, 0, strlen($userStr) - 1);
				$userQuery = "SELECT * FROM user WHERE user_name IN ($userStr)";
				$userResult = $conn->query($userQuery);
				if ($userResult->num_rows > 0) {
					$notificationContent = array(
						"user" => $userData,
						"post" => $data
					);
					$notifContent = json_encode($notificationContent);
					
					$users = $userResult->fetch_assoc();

					while ($row = $userResult->fetch_assoc()) {

						$receiverId = $row["id"];
						$query = "INSERT INTO notification(receiver_id, content, type, object_id, post_id) VALUES($receiverId, '$notifContent', 2, '$post_id', '$post_id')";
						$conn->query($query);
					}
					// foreach ($users as $row) {
					// var_dump($row);
					// 	$receiverId = $row["id"];
					// 	$query = "INSERT INTO notification(receiver_id, content, type) VALUES($receiverId, '$notifContent', 2)";
					// 	$conn->query($query);
					// }
					
					
				}
			}
		} else {
			$response["status"] = FAILED;
			$response["message"] = "Insert was failed";
		}
	} else {
		$response["status"] = FAILED;
		$response["message"] = "Upload was failed.";
	}

	return $response;
	
}

function likevideo($user_id, $post_id, $status = 0, $conn) {
	$response = array();

	$query = "SELECT * FROM post_like WHERE user_id=$user_id AND post_id=$post_id";
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		$resultData = $result->fetch_assoc();
		if ($resultData['like_status'] == $status) {
			$response["status"] = FAILED;
			if ($status == 0) {
				$response["message"] = "You already liked this video.";
			} else {
				$response["message"] = "You already disliked this video.";
			}
		} else {
			$query = "UPDATE post_like SET like_status='$status' WHERE user_id=$user_id AND post_id=$post_id";
			$result = $conn->query($query);
			if ($result) {
				$response["status"] = SUCCESS;
				$response["message"] = "Success";
			} else {
				$response["status"] = FAILED;
				$response["message"] = "Failed";
			}
		}
	} else {
		$query = "INSERT INTO post_like(user_id, post_id, like_status) VALUES ($user_id, $post_id, $status)";
		$state = $conn->query($query);
		if ($state) {

			$postQuery = "SELECT * FROM post WHERE id=$post_id";
			$postResult = $conn->query($postQuery);
			$postData = $postResult->fetch_assoc();
			$post_id = $postData['id'];

			$receiverId = $postData["user_id"];
			$userQuery = "SELECT * FROM user WHERE id=$user_id";
			$userResult = $conn->query($userQuery);
			$userData = $userResult->fetch_assoc();
			unset($userData["authkey"]);
			unset($userData["password"]);
			$notificationContent = array(
					"user" => $userData,
					"post" => $postData
				);
			$content = json_encode($notificationContent);

			$query = "INSERT INTO notification(receiver_id, content, type, object_id, post_id) VALUES ($receiverId, '$content', 4, '$post_id', '$post_id')";
			$conn->query($query);

			$response["status"] = SUCCESS;
			$response["message"] = "Success";
		} else {
			$response["status"] = FAILED;
			$response["message"] = "Failed";
		}
	}
	return $response;
}

function unlikevideo($user_id, $post_id, $conn) {
	$response = array();

	$query = "DELETE FROM post_like WHERE user_id=$user_id AND post_id=$post_id";
	$state = $conn->query($query);
	if ($state) {
		$response["status"] = SUCCESS;
		$response["message"] = "You disliked this video.";
	} else {
		$response["status"] = FAILED;
		$response["message"] = "Failed to dislike";
	}
	return $response;
}
function viewvideo($user_id, $post_id, $conn) {
	$response = array();

	$query = "SELECT * FROM post_view WHERE user_id=$user_id AND post_id=$post_id";
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		$response["status"] = FAILED;
		$response["message"] = "You already viewed this video.";
	} else {
		$query = "INSERT INTO post_view(user_id, post_id) VALUES ($user_id, $post_id)";
		$state = $conn->query($query);
		if ($state) {
			$response["status"] = SUCCESS;
			$response["message"] = "Success";
		} else {
			$response["status"] = FAILED;
			$response["message"] = "Failed";
		}
	}
	return $response;
}
function likeChallenge($user_id, $ch_id, $status = 0, $conn) {
	$response = array();

	$query = "SELECT * FROM challenge_like WHERE user_id=$user_id AND ch_id=$ch_id";
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		$resultData = $result->fetch_assoc();
		if ($resultData['like_status'] == $status) {
			$response["status"] = FAILED;
			if ($status == 0) {
				$response["message"] = "You already liked this challenge video.";
			} else {
				$response["message"] = "You already disliked this challenge video.";
			}
		} else {
			$query = "UPDATE challenge_like SET like_status='$status' WHERE user_id=$user_id AND ch_id=$ch_id";
			$result = $conn->query($query);
			if ($result) {
				$response["status"] = SUCCESS;
				$response["message"] = "Success";
			} else {
				$response["status"] = FAILED;
				$response["message"] = "Failed";
			}
		}
	} else {
		$query = "INSERT INTO challenge_like(ch_id, user_id, like_status) VALUES($ch_id, $user_id, $status)";
		$state = $conn->query($query);
		if ($state) {
			$chQuery = "SELECT * FROM challenge WHERE id=$ch_id";
			$chResult = $conn->query($chQuery);
			$chData = $chResult->fetch_assoc();
			$ch_id = $chData['id'];

			$receiverId = $chData["user_id"];
			$userQuery = "SELECT * FROM user WHERE id=$user_id";
			$userResult = $conn->query($userQuery);
			$userData = $userResult->fetch_assoc();
			unset($userData["authkey"]);
			unset($userData["password"]);
			
			$notificationContent = array(
					"user" => $userData,
					"challenge" => $chData
				);
			$content = json_encode($notificationContent);

			$query = "INSERT INTO notification(receiver_id, content, type, object_id, post_id) VALUES ($receiverId, '$content', 5, '$ch_id', '$ch_id')";
			$conn->query($query);

			$response["status"] = SUCCESS;
			$response["message"] = "Liked";
		} else {
			$response["status"] = FAILED;
			$response["message"] = "Failed to insert like challenge ";
		}
	}
	return $response;
}

function unlikeChallenge($user_id, $ch_id, $conn) {
	$response = array();

	$query = "DELETE FROM challenge_like WHERE user_id=$user_id AND ch_id=$ch_id";
	$state = $conn->query($query);
	if ($state) {
		$response["status"] = SUCCESS;
		$response["message"] = "You disliked this video.";
	} else {
		$response["status"] = FAILED;
		$response["message"] = "Failed to dislike challenge ";
	}
	return $response;
}

function comment($user_id, $post_id, $content, $conn) {
	$response = array();
	
	$query = 'INSERT INTO post_comment(post_id, user_id, content) VALUES ( ' . $post_id . ' , ' . $user_id . ', "' . $content . '")';
	// var_dump($query);
	$status = $conn->query($query);
	if ($status) {
		
		$postQuery = "SELECT * FROM post WHERE id=$post_id";
		$postResult = $conn->query($postQuery);
		$postData = $postResult->fetch_assoc();
		$post_id = $postData['id'];
		//get post comment
		$postCommentQuery = "SELECT * FROM post_comment ORDER BY id DESC LIMIT 1";
		$postCommentResult = $conn->query($postCommentQuery);
		$postCommentData = $postCommentResult->fetch_assoc();
		$postComment_id = $postCommentData['id'];

		$userQuery = "SELECT * FROM user WHERE id=$user_id";
		$userResult = $conn->query($userQuery);
		$userData = $userResult->fetch_assoc();
		unset($userData["authkey"]);
		unset($userData["password"]);
		
		$strArr = filter_username($content);
		$userStr = "";
		
		if (count($strArr) > 0) {
			foreach ($strArr as $value) {
				$userStr .= "'" . $value . "',";	
			}
			$userStr = substr($userStr, 0, strlen($userStr) - 1);
			$userQuery = "SELECT * FROM user WHERE user_name IN ($userStr)";

			$userResult = $conn->query($userQuery);
			if ($userResult->num_rows > 0) {
				$notificationContent = array(
						"user" => $userData,
						"post" => $postData
					);
				$notifContent = json_encode($notificationContent);

				while ($row = $userResult->fetch_assoc()) {
					$receiverId = $row["id"];	
					$query = "INSERT INTO notification(receiver_id, content, type, object_id, post_id) VALUES($receiverId, '$notifContent', 2, $postComment_id, $post_id)";
					$result = $conn->query($query);
				}
			}
		}
		
		$response["status"] = SUCCESS;
		$response["message"] = "Success";
		
	} else {
		$response["status"] = FAILED;
		$response["message"] = "Failed to add comment";
	}
	return $response;
}

function isChallengable($post_id, $conn) {
	$query = "SELECT IF(a.ch_count > IFNULL(b.cnt, 0), 1, 0) as isAvailable FROM post a
				LEFT JOIN (SELECT post_id, count(id) cnt FROM challenge GROUP BY post_id) b ON a.id=b.post_id
				WHERE a.id=$post_id";
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		return $row["isAvailable"];
	} else {
		return 0;
	}
}

function challengevideo($user_id, $post_id, $thumbnail_url, $conn) {
	$response = array();

	if (isChallengable($post_id, $conn) == 0) {
		$response["status"] = FAILED;
		$response["message"] = "Challenge count has limited";
		return $response;
	}
	
	$targetPath = "../uploads/videos/";
	$fileName = $_FILES["file"]["name"];
	$targetPath .= $fileName;
	if ($_FILES["file"]["size"] > UPLOAD_MAX_SIZE) {
		$response["status"] = FAILED;
		$response["message"] = "File size is very large.";
		return $response;
	}
	if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetPath)) {
		$video_name = $fileName;
		$video_url = BASE_URL."/uploads/videos/".$video_name;
		$query = "INSERT INTO challenge(user_id, post_id, video_name, video_url, thumbnail_url)
					VALUES ($user_id, $post_id, '$video_name', '$video_url', '$thumbnail_url')";
		$state = $conn->query($query);
		if ($state) {
			//add notification
			$postQuery = "SELECT * FROM post WHERE id=$post_id";
			$postResult = $conn->query($postQuery);
			$postData = $postResult->fetch_assoc();
			$post_id = $postData['id'];
			$receiverId = $postData["user_id"];
			$userQuery = "SELECT * FROM user WHERE id=$user_id";
			$userResult = $conn->query($userQuery);
			$userData = $userResult->fetch_assoc();
			unset($userData["authkey"]);
			unset($userData["password"]);
			$chData = array(
					"id" => $conn->insert_id,
					"post_id" => $post_id,
					"user_id" => $user_id,
					"video_name" => $video_name,
					"video_url" => $video_url,
					"thumbnail_url" => $thumbnail_url
					
				);
			$notificationContent = array(
					"user" => $userData,
					"challenge" => $chData
				);
			$content = json_encode($notificationContent);

			$query = "INSERT INTO notification(receiver_id, content, type, object_id, post_id) VALUES ($receiverId, '$content', 7, '$post_id', '$post_id')";
			$conn->query($query);

			//build response
			$response["status"] = SUCCESS;
			$response["message"] = "Upload was successed.";
			$data = array(
					"id" => $conn->insert_id,
					"post_id" => $post_id,
					"user_id" => $user_id,
					"video_name" => $video_name,
					"video_url" => $video_url,
					"thumbnail_url" => $thumbnail_url
				);
		} else {
			$response["status"] = FAILED;
			$response["message"] = "Insert was failed";
		}
	} else {
		$response["status"] = FAILED;
		$response["message"] = "Upload was failed.";
	}

	return $response;
	
}

function get_populars($conn) {
	$response = array();
	$query = "SELECT a.*, IFNULL(b.like_count, 0) as like_count, IF(a.ch_count > IFNULL(c.cnt, 0), 1, 0) as isChallengable, 
				u.user_name FROM post a
				LEFT JOIN user u ON u.id=a.user_id 
				LEFT JOIN (SELECT post_id, count(id) as like_count FROM post_like GROUP BY post_id) b ON a.id=b.post_id
				LEFT JOIN (SELECT post_id, count(id) cnt FROM challenge GROUP BY post_id) c ON a.id=c.post_id
				ORDER BY like_count DESC, a.create_time DESC";
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		$response["status"] = SUCCESS;
		$response["message"] = $result->num_rows."rows";
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		$response["data"] = $data;
	} else {
		$response["status"] = SUCCESS;
		$response["message"] = "Empty row";
		$response["data"] = array();
	}
	return $response;
}


function get_challenges($conn) {
	$response = array();
	$query = "	SELECT c.*, IF(c.cnt > 0, 1, 0) as isChallengable FROM 	
					(SELECT a.*, (a.ch_count-IFNULL(b.cnt, 0)) as cnt, u.user_name FROM post a
					LEFT JOIN (SELECT post_id, count(id) as cnt FROM challenge GROUP BY post_id) b ON a.id=b.post_id
					LEFT JOIN user u ON u.id=a.user_id) c WHERE c.cnt>0
				ORDER BY c.cnt, c.create_time DESC";
	
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		$response["status"] = SUCCESS;
		$response["message"] = $result->num_rows."rows";
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		$response["data"] = $data;
	} else {
		$response["status"] = SUCCESS;
		$response["message"] = "Empty row";
		$response["data"] = array();
	}
	return $response;
}

function get_recents($conn, $offset) {
	$response = array();
	$query = "	SELECT a.*, IF(a.ch_count > IFNULL(c.cnt, 0), 1, 0) as isChallengable, u.user_name FROM post a
				LEFT JOIN (SELECT post_id, count(id) cnt FROM challenge GROUP BY post_id) c ON a.id=c.post_id
				LEFT JOIN user u ON u.id=a.user_id
				ORDER BY a.create_time DESC";

	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		$response["status"] = SUCCESS;
		$response["message"] = $result->num_rows."rows";
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		if ($offset >= 0) {
			$data = array_slice($data, $offset * 60, 60);
		}
		$response["data"] = $data;
	} else {
		$response["status"] = SUCCESS;
		$response["message"] = "Empty row";
		$response["data"] = array();
	}
	return $response;
}

function get_video($user_id, $post_id, $conn) {
	$response = array();
	$query = "	SELECT a.*, b.fname, b.lname, b.user_name,  IFNULL(c.isViewed, 0) as isViewed, IFNULL(d.iscomment, 0) as iscomment, IFNULL(e.isreply, 0) as isreply, IF(a.ch_count > IFNULL(f.cnt, 0), 1, 0) as isChallengable FROM post a
				LEFT JOIN user b ON a.user_id=b.id
				LEFT JOIN (SELECT post_id, COUNT(id) as isViewed FROM post_view WHERE user_id=$user_id GROUP BY post_id) c ON a.id=c.post_id
				LEFT JOIN (SELECT post_id, COUNT(id) as iscomment FROM post_comment WHERE user_id=$user_id GROUP BY post_id) d ON a.id=d.post_id
				LEFT JOIN (SELECT post_id, COUNT(id) as isreply FROM challenge WHERE user_id=$user_id GROUP BY post_id) e on a.id=e.post_id
				LEFT JOIN (SELECT post_id, COUNT(id) as cnt FROM challenge GROUP BY post_id) f ON a.id=f.post_id
				WHERE a.id=$post_id";
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		$response["status"] = SUCCESS;
		$response["message"] = "success";

		$row = $result->fetch_assoc();
		$row['like_count'] = get_like_count($post_id, "post", 0, $conn);
		$row['trash_count'] = get_like_count($post_id, "post", 1, $conn);
		$row['comment_count'] = get_comment_count($post_id, "post", $conn);
		$row['view_count'] = get_viewed_count($post_id, $conn);
		$row['challenge_count'] = get_challenge_count($post_id, $conn);

		$row['like_status'] = get_like_status($user_id, $post_id, "post", $conn);
		$response["data"] = $row;
	} else {
		$response["status"] = FAILED;
		$response["message"] = "No result";
	}
	return $response;
}
function get_like_status($user_id, $id, $type, $conn)
{
	$query = "";
	if ($type == "post") {
		$query = "SELECT * FROM post_like WHERE post_id=$id AND user_id=$user_id";
	} else {
		$query = "SELECT * FROM challenge_like WHERE ch_id=$id AND user_id=$user_id";
	}
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		return $row['like_status'];

	} else {
		return -1;		
	}
}
function get_like_count($id, $type = "post", $status = 1, $conn) {// type can be "post" and "challenge"
	$query = "";
	if ($type == "post") {
		$query = "SELECT * FROM post_like WHERE post_id=$id AND like_status=$status";
	} else {
		$query = "SELECT * FROM challenge_like WHERE ch_id=$id AND like_status=$status";
	}
	$result = $conn->query($query);
	return $result->num_rows;

}
function get_viewed_count($post_id, $conn) {
	$query = "SELECT * FROM post_view WHERE post_id=$post_id";
	$result = $conn->query($query);
	return $result->num_rows;
}
function get_challenge_count($post_id, $conn) {
	$query = "SELECT * FROM challenge WHERE post_id=$post_id";
	$result = $conn->query($query);
	return $result->num_rows;
}
function get_comment_count($id, $type = "post", $conn) {// type can be "post" and "challenge"
	$query = "";
	if ($type == "post") {
		$query = "SELECT * FROM post_comment WHERE post_id=$id";
	} else {
		$query = "SELECT * FROM challenge_comment WHERE challenge_id=$id";
	}
	$result = $conn->query($query);
	return $result->num_rows;

}

function get_challenge($post_id, $user_id, $conn) {
	$response = array();
	$query = "	SELECT a.id, a.post_id, a.user_id, a.video_name, a.video_url, a.thumbnail_url, a.create_time, b.user_name, b.avatar_name, b.avatar_url,
						IFNULL(c.iscomment, 0) as iscomment
				FROM challenge a
				LEFT JOIN user b ON a.user_id=b.id 
				LEFT JOIN (SELECT challenge_id, COUNT(id) as iscomment FROM challenge_comment WHERE user_id=$user_id GROUP BY challenge_id) c ON a.id=c.challenge_id
				WHERE a.post_id=$post_id ORDER BY a.create_time DESC";
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		$response["status"] = SUCCESS;
		$response["message"] = $result->num_rows."rows";

		$data = array();
		while ($row = $result->fetch_assoc()) {
			$row['like_count'] = get_like_count($row['id'], "challenge", 0, $conn);
			$row['trash_count'] = get_like_count($row['id'], "challenge", 1, $conn);
			$row['comment_count'] = get_comment_count($row['id'], "challenge", $conn);
			$row['like_status'] = get_like_status($user_id, $row['id'], "challenge", $conn);
			$data[] = $row;
		}
		$response["data"] = $data;
	} else {
		$response["status"] = SUCCESS;
		$response["message"] = "Empty row";
		$response["data"] = array();
	}
	return $response;
}

function get_comment($post_id, $conn) {
	$response = array();
	$query = "SELECT a.content, a.id as comment_id, b.id , b.user_name, b.avatar_name, b.avatar_url FROM post_comment a
				LEFT JOIN user b on a.user_id=b.id
				WHERE a.post_id=$post_id ORDER BY a.create_time";
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		$response["status"] = SUCCESS;
		$response["message"] = $result->num_rows."rows";
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		$response["data"] = $data;
	} else {
		$response["status"] = SUCCESS;
		$response["message"] = "Empty row";
		$response["data"] = array();
	}
	return $response;
}
function get_followers($user_id, $follow_type, $conn) {
	$response = array();
	$query = "";
	if ($follow_type == "FOLLOWINGS") {
		$query = "SELECT a.* FROM user a
				LEFT JOIN follow b on a.id=b.following_id
				WHERE b.follower_id=$user_id ORDER BY b.created_time";
	} else {
		$query = "SELECT a.* FROM user a
				LEFT JOIN follow b on a.id=b.follower_id
				WHERE b.following_id=$user_id ORDER BY b.created_time";
	}
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		$response["status"] = SUCCESS;
		$response["message"] = $result->num_rows."rows";
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		$response["data"] = $data;
	} else {
		$response["status"] = SUCCESS;
		$response["message"] = "Empty row";
		$response["data"] = array();
	}
	return $response;
}
function update_profile($authkey, $bio, $username, $avatar_name, $avatar_url, $conn) {
	$response = array();
	$bio = escape_string($bio);
	$query = "";
	if (empty($username)) {
		$response["status"] = FAILED;
		$response["messages"] = "Empty username";
	} else {
		if (empty($avatar_name)) {
			$query = "UPDATE user SET bio='$bio', user_name='$username' WHERE authkey='$authkey' ";
		} else {
			$query = "UPDATE user SET bio='$bio', user_name='$username', avatar_name='$avatar_name', avatar_url='$avatar_url' WHERE authkey='$authkey' ";
		}

		$status = $conn->query($query);
		if ($status) {
			$query = "SELECT * FROM user WHERE authkey='$authkey'";
			$result = $conn->query($query);
			$row = $result->fetch_assoc();
			$data = array(
					'user_id' => $row["id"],
					'user_name' => $row["user_name"],
					'fname' => $row["fname"],
					'lname' => $row["lname"],
					'email' => $row["email"],
					'birthday' => $row["birthday"],
					'authkey' => $authkey,
					'avatar_name' => $row["avatar_name"],
					'avatar_url' => $row["avatar_url"],
					'bio' => $row["bio"]
				);
			$response["status"] = SUCCESS;
			$response["message"] = "Profile was updated";
			$response["data"] = $data;
		} else {
			$response["status"] = FAILED;
			$response["message"] = "Update profile was failed";
		}
	}
	
	return $response;
}

function follow($follower_id, $following_id, $conn) {
	$response = array();

	// Follower
	$query = "SELECT * FROM follow WHERE following_id=$following_id AND follower_id=$follower_id";
	$result = $conn->query($query);

	$followers = 0;
	if ($result->num_rows == 0) {
		$newQuery = "INSERT INTO follow(follower_id, following_id) VALUES($follower_id, $following_id)";
		$conn->query($newQuery);
		$insertId = $conn->insert_id;

		$userQuery = "SELECT * FROM user WHERE id=$follower_id";
		$userResult = $conn->query($userQuery);
		$userData = $userResult->fetch_assoc();
		$user_id = $userData['id'];
		unset($userData["authkey"]);
		unset($userData["password"]);
		$notificationContent = array(
				"user" => $userData,
				"follow_id" => $insertId
			);
		$content = json_encode($notificationContent);

		$query = "INSERT INTO notification(receiver_id, content, type, object_id, post_id) VALUES ($following_id, '$content', 1, '$user_id', 0)";
		$conn->query($query);

	}
	$query1 = "SELECT SUM(a.follower_count) as follower_count, SUM(a.following_count) as following_count FROM
				(SELECT 0 as follower_count, count(*) as following_count FROM follow WHERE follower_id=$following_id 
 				 UNION ALL
				 SELECT count(*) as follower_count, 0 as following_count FROM follow WHERE following_id=$following_id ) as a";
	$result = $conn->query($query1);
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$follower_count = $row["follower_count"];
		$following_count = $row["following_count"];

		$data = array(
				"follower_count" => $follower_count,
				"following_count" => $following_count
			);
		$response["data"] = $data;
		$response["status"] = SUCCESS;
		$response["message"] = SUCCESS;
	} else {
		$response["status"] = SUCCESS;
		$response["message"] = SUCCESS;
	}
	
	return $response;	
}

function unfollow($follower_id, $following_id, $conn) {
	$response = array();

	$query = "DELETE FROM follow WHERE follower_id=$follower_id AND following_id=$following_id";
	$conn->query($query);
	
	$query1 = "SELECT SUM(a.follower_count) as follower_count, SUM(a.following_count) as following_count FROM
				(SELECT 0 as follower_count, count(*) as following_count FROM follow WHERE follower_id=$following_id
 				 UNION ALL
				 SELECT count(*) as follower_count, 0 as following_count FROM follow WHERE following_id=$following_id) as a";
	$result = $conn->query($query1);
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$follower_count = $row["follower_count"];
		$following_count = $row["following_count"];

		$data = array(
				"follower_count" => $follower_count,
				"following_count" => $following_count
			);
		$response["data"] = $data;
		$response["status"] = SUCCESS;
		$response["message"] = SUCCESS;
	} else {
		$response["status"] = SUCCESS;
		$response["message"] = SUCCESS;
	}
	
	return $response;
}

function approve_friend_request($notification_id, $follow_id, $state, $conn) {
	$response = array();

	$query = "";
	if ($state == 1) {
		$query = "UPDATE follow SET status=1 WHERE id=$follow_id";
	} else {
		$query = "DELETE FROM follow WHERE id=$follow_id";
	}
	$status = $conn->query($query);
	if ($status) {
		$deleteQuery = "DELETE FROM notification WHERE id=$notification_id";
		$conn->query($deleteQuery);
		$response["status"] = SUCCESS;
		$message = "";
		if ($state == 1) {
			$message = "Approved the request";
		} else {
			$message = "Ignored the request";
		}
		$response["message"] = $message;
	} else {
		$response["status"] = FAILED;
		$response["message"] = "Failed to update friend status";
	}
	return $response;
}

function get_userinfo($authkey, $user_id, $my_id, $conn) {
	$response = array();
	$myId = getUserId($authkey, $conn);
	$query = "	SELECT a.*, IFNULL(b.followers, 0) as followers, IFNULL(b.followings, 0) as followings, IF(IFNULL(c.cnt, 0) > 0, 1, 0) as isReported, IFNULL(d.state, 0) as isBlocked
				FROM user a
				LEFT JOIN (SELECT f.user_id, SUM(f.followers) as followers, SUM(f.followings) as followings FROM 
						(SELECT following_id as user_id, count(*) as followers, 0 as followings FROM follow WHERE following_id=$user_id
						UNION ALL
						SELECT follower_id as user_id, 0 as followers, count(*) as followings FROM follow WHERE follower_id=$user_id) f
						GROUP BY f.user_id) b 
					ON a.id=b.user_id
				LEFT JOIN (SELECT user_id, count(*) as cnt FROM report WHERE user_id=$user_id AND by_userid=$myId) c ON a.id=c.user_id
				LEFT JOIN (SELECT user_id, state FROM block WHERE user_id=$user_id AND by_userid=$myId) d ON a.id=d.user_id 
				WHERE a.id=$user_id	";
	
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		# code...
		$user = $result->fetch_assoc();

		$videoQuery = " SELECT p.*, IF(p.ch_count > IFNULL(f.cnt, 0), 1, 0) as isChallengable 
						FROM post p
						LEFT JOIN (SELECT post_id, count(id) cnt FROM challenge GROUP BY post_id) f ON p.id=f.post_id
						WHERE p.user_id=$user_id
						ORDER BY p.create_time DESC";
		$videoResult = $conn->query($videoQuery);
		$videos = array();
		if ($videoResult->num_rows > 0) {
			while ($row = $videoResult->fetch_assoc()) {
				$videos[] = $row;
			}
		}
		$data = array(
					'id' => $user["id"],
					'user_name' => $user["user_name"],
					'fname' => $user["fname"],
					'lname' => $user["lname"],
					'email' => $user["email"],
					'birthday' => $user["birthday"],
					'avatar_name' => $user["avatar_name"],
					'avatar_url' => $user["avatar_url"],
					'bio' => $user["bio"],
					'followers' => $user["followers"],
					'followings' => $user["followings"],
					'isReported' => $user["isReported"],
					'isBlocked' => $user["isBlocked"],
					'isFollowing' => isFollowing($my_id, $user_id, $conn),
					'like_count' => get_profile_like_count($user_id, '0', $conn),
					'trash_count' => get_profile_like_count($user_id, '1', $conn),
					'comment_count' => get_profile_comment_count($user_id, $conn),
					'challenge_count' => get_profile_challenge_count($user_id, $conn),
					'view_count' => get_profile_view_count($user_id, $conn),
					'videos' => $videos
				);
		$response["status"] = SUCCESS;
		$response["message"] = SUCCESS;
		$response["data"] = $data;
	} else {
		$response["status"] = FAILED;
		$response["message"] = "No result";
	}
	return $response;
}
function get_profile_like_count($user_id, $like_status = '0', $conn) 
{
	$query = "SELECT a.* FROM post_like a
				LEFT JOIN post u ON u.id=a.post_id
				WHERE u.user_id=$user_id AND a.like_status=$like_status";
	$result = $conn->query($query);
	return $result->num_rows;
}
function get_profile_comment_count($user_id, $conn)
{
	$query = "SELECT a.* FROM post_comment a
				LEFT JOIN post u ON u.id=a.post_id
				WHERE u.user_id=$user_id";
	$result = $conn->query($query);
	return $result->num_rows;
}
function get_profile_challenge_count($user_id, $conn) 
{
	$query = "SELECT a.* FROM challenge a
				LEFT JOIN post u ON u.id=a.post_id
				WHERE u.user_id=$user_id";
	$result = $conn->query($query);
	return $result->num_rows;
}
function get_profile_view_count($user_id, $conn) 
{
	$query = "SELECT a.* FROM post_view a
				LEFT JOIN post u ON u.id=a.post_id
				WHERE u.user_id=$user_id";
	$result = $conn->query($query);
	return $result->num_rows;
}
function getUserId($authkey, $conn) {
	$query = "SELECT id FROM user WHERE authkey='$authkey'";
	$result = $conn->query($query);
	$row = $result->fetch_assoc();
	return $row["id"];
}

function report($user_id, $by_userid, $content, $conn) {
	$response = array();
	$query = "INSERT INTO report(user_id, by_userid, content) VALUES($user_id, $by_userid, '$content') ";
	$state = $conn->query($query);
	if ($state) {
		# code...
		$response["status"] = SUCCESS;
		$response["message"] = "Report was successed";
	} else {
		$response["status"] = FAILED;
		$response["message"] = "Report was failed";
	}
	return $response;
}

function block($user_id, $by_userid, $state, $conn) {
	$response = array();
	$query = "SELECT * FROM block WHERE user_id=$user_id AND by_userid=$by_userid";
	$result = $conn->query($query);
	$newQuery = "";
	if ($result->num_rows > 0) {
		$newQuery = "UPDATE block SET state=$state WHERE user_id=$user_id AND by_userid=$by_userid";
	} else {
		$newQuery = "INSERT INTO block(user_id, by_userid, state) VALUES($user_id, $by_userid, $state)";
	}
	$conn->query($newQuery);

	$response["status"] = SUCCESS;
	$response["message"] = SUCCESS;
	return $response;
}

function challenge_comment($user_id, $challenge_id, $content, $conn) {
	$response = array();
	$query = 'INSERT INTO challenge_comment(challenge_id, user_id, content) VALUES ( ' .$challenge_id. ',' . $user_id .', "'.$content . '")';
	$status = $conn->query($query);
	if ($status) {
		$response["status"] = SUCCESS;
		$response["message"] = "Success";
		
		$chQuery = "SELECT * FROM challenge WHERE id=000";
		$chResult = $conn->query($chQuery);
		$chData = $chResult->fetch_assoc();
		$ch_id = $chData['id'];
		//get challenge comment id
		$chCommentQuery = "SELECT * FROM challenge_comment ORDER BY id DESC LIMIT 1";
		$chCommentResult = $conn->query($chCommentQuery);
		$chCommentData = $chCommentResult->fetch_assoc();
		$chComment_id = $chCommentData['id'];

		$userQuery = "SELECT * FROM user WHERE user_name IN ('$content')";
		$userResult = $conn->query($userQuery);
		$userData = $userResult->fetch_assoc();
		if ($result->num_rows > 0) {
			unset($userData["authkey"]);
			unset($userData["password"]);
		}
		
		$strArr = filter_username($content);
		$userStr = "";
		if (count($strArr) > 0) {
			foreach ($strArr as $value) {
				$userStr .= "'" . $value . "',";	
			}
			$userStr = substr($userStr, 0, strlen($userStr) - 1);
			$userQuery = "SELECT * FROM user WHERE user_name IN ($userStr)";
			$userResult = $conn->query($userQuery);
			if ($userResult->num_rows > 0) {
				$notificationContent = array(
						"user" => $userData,
						"challenge" => $chData
					);
				$notifContent = json_encode($notificationContent);

				while ($row = $userResult->fetch_assoc()) {
					$receiverId = $row["id"];	
					$query = "INSERT INTO notification(receiver_id, content, type, object_id, post_id) VALUES($receiverId, '$notifContent', 3, '$chComment_id', $ch_id')";
					$conn->query($query);
				}
			}
		}

	} else {
		$response["status"] = FAILED;
		$response["message"] = "Failed to challenge comment";
	}
	return $response;
}

function get_challenge_comment($challenge_id, $conn) {
	$response = array();
	$query = "	SELECT a.content, a.id as comment_id, b.id, b.user_name, b.avatar_url FROM challenge_comment a
				LEFT JOIN user b on a.user_id=b.id
				WHERE a.challenge_id=$challenge_id ORDER BY a.create_time";
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		$response["status"] = SUCCESS;
		$response["message"] = $result->num_rows."rows";
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		$response["data"] = $data;
	} else {
		$response["status"] = SUCCESS;
		$response["message"] = "Empty row";
		$response["data"] = array();
	}
	return $response;
}

function ignore_notification($id, $conn) {
	$response = array();
	$query = "UPDATE notification SET status=2 WHERE id=$id";
	$conn->query($query);
	$response["status"] = SUCCESS;
	$response["message"] = SUCCESS;
	return $response;
}

function get_notification($user_id, $type, $conn) {
	$response = array();
	$data = array();

	$query = "SELECT * FROM notification WHERE receiver_id=$user_id AND type IN ($type) AND status IN (0,1) ORDER BY created_time DESC";
	$result = $conn->query($query);

	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
	}
	$response["data"] = $data;
	$response["status"] = SUCCESS;
	$response["message"] = SUCCESS;
	return $response;
}

function view_notification($id, $conn) {
	$response = array();
	$query = "UPDATE notification SET status=1 WHERE id=$id";
	$conn->query($query);
	$response["status"] = SUCCESS;
	$response["message"] = SUCCESS;
	return $response;	
}

function search_people($keyword, $conn) {
	$response = array();
	$data = array();
	$query = "SELECT * FROM user WHERE user_name LIKE '%$keyword%' ";
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			unset($row["password"]);
			unset($row["authkey"]);
			$data[] = $row;
		}
	}

	$response["status"] = SUCCESS;
	$response["message"] = SUCCESS;
	$response["data"] = $data;
	return $response;
}

function search_video($keyword, $conn) {
	$response = array();
	$query = "SELECT a.*, IFNULL(b.like_count, 0) as like_count, IF(a.ch_count > IFNULL(c.cnt, 0), 1, 0) as isChallengable, u.user_name FROM post a
				LEFT JOIN user u ON u.id=a.user_id
				LEFT JOIN (SELECT post_id, count(id) as like_count FROM post_like GROUP BY post_id) b ON a.id=b.post_id
				LEFT JOIN (SELECT post_id, count(id) cnt FROM challenge GROUP BY post_id) c ON a.id=c.post_id
				WHERE a.ch_title LIKE '%$keyword%'
				ORDER BY like_count DESC, a.create_time DESC";

	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		$response["status"] = SUCCESS;
		$response["message"] = $result->num_rows."rows";
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		$response["data"] = $data;
	} else {
		$response["status"] = SUCCESS;
		$response["message"] = "Empty row";
		$response["data"] = array();
	}
	return $response;
}

function change_password($authkey, $old_password, $new_password, $conn) {
	$response = array();
	$old_password = md5($old_password.AUTH_SURFIX);
	$query = "SELECT * FROM user WHERE authkey='$authkey'";
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		if ($row["password"] == $old_password) {
			$new_password = md5($new_password.AUTH_SURFIX);
			$query = "UPDATE user SET password='$new_password' WHERE authkey='$authkey'";
			$conn->query($query);
			$response["status"] = SUCCESS;
			$response["message"] = "Your password was updated correctly";
		} else {
			$response["status"] = FAILED;
			$response["message"] = "Current password is incorrect";
		}
	} else {
		$response["status"] = FAILED;
		$response["message"] = "Invalid authkey";
	}
	return $response;
}
function isFollowing ($my_id, $user_id, $conn) {
	$query = "SELECT * FROM follow WHERE follower_id='$my_id' AND following_id='$user_id'";
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		return '1';
	}
	return '0';
}
function isValidAuthKey($authkey, $conn) {
	$query = "SELECT * FROM user WHERE authkey='$authkey'";
	$result = $conn->query($query);
	return $result->num_rows;
}

function gen_authkey() {
	$string = uniqid().AUTH_SURFIX;
	$str = "";
	for ($i = 0; $i < 25; $i++) {
		$pos = rand(0, 61);
		$str .= $string[$pos];
	}
	return $str;
}

function filter_username($content) {
	$strArr = array();
	$arr = explode(" ", trim($content));
	foreach ($arr as $value) {
		if (startsWith($value, "@")) {
			$strArr[] = substr($value, 1);
		}
	}
	return $strArr;
}

function startsWith($haystack, $needle) {
	return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

function escape_string($string) {
    return str_replace(
                    array('!','"','#','$','%','&',"'",'(',')','*','+',',','-','.','/',':',';','<','=','>','?','@','[','\\',']','^','_','{','|','}','~'),
                    array('\!','\"','\#','\$','\%','\&',"\'",'\(','\)','\*','\+','\,','\-','\.','\/','\:','\;','\<','\=','\>','\?','\@','\[','\\','\]','\^','\_','\{','\|','\}','\~'),
                    $string);
}

function spellCheck($input) {
	if (preg_match('/[^a-zA-Z0-9]+/', $input)) {
		return false;
	} else {
		return true;
	}
}

$conn->close();
echo(json_encode($value));
exit();
?>