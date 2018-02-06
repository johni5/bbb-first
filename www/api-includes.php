<?php
include_once("lib/Parameters/BaseParameters.php");
include_once("lib/Parameters/MetaParameters.php");
include_once("lib/Parameters/CreateMeetingParameters.php");
include_once("lib/Parameters/DeleteRecordingsParameters.php");
include_once("lib/Parameters/EndMeetingParameters.php");
include_once("lib/Parameters/GetMeetingInfoParameters.php");
include_once("lib/Parameters/GetRecordingsParameters.php");
include_once("lib/Parameters/IsMeetingRunningParameters.php");
include_once("lib/Parameters/JoinMeetingParameters.php");
include_once("lib/Parameters/PublishRecordingsParameters.php");
include_once("lib/Parameters/SetConfigXMLParameters.php");
include_once("lib/Parameters/UpdateRecordingsParameters.php");

include_once("lib/Responses/BaseResponse.php");
include_once("lib/Responses/ApiVersionResponse.php");
include_once("lib/Responses/CreateMeetingResponse.php");
include_once("lib/Responses/DeleteRecordingsResponse.php");
include_once("lib/Responses/EndMeetingResponse.php");
include_once("lib/Responses/GetDefaultConfigXMLResponse.php");
include_once("lib/Responses/GetMeetingInfoResponse.php");
include_once("lib/Responses/GetMeetingsResponse.php");
include_once("lib/Responses/GetRecordingsResponse.php");
include_once("lib/Responses/IsMeetingRunningResponse.php");
include_once("lib/Responses/JoinMeetingResponse.php");
include_once("lib/Responses/PublishRecordingsResponse.php");
include_once("lib/Responses/SetConfigXMLResponse.php");
include_once("lib/Responses/UpdateRecordingsResponse.php");

include_once("lib/Exceptions/ChecksumException.php");

include_once("lib/Core/ApiMethod.php");
include_once("lib/Core/Attendee.php");
include_once("lib/Core/Meeting.php");
include_once("lib/Core/MeetingInfo.php");
include_once("lib/Core/Record.php");

include_once("lib/Util/UrlBuilder.php");

include_once("lib/BigBlueButton.php");