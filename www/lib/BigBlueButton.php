<?php
/**
 * BigBlueButton open source conferencing system - http://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016 BigBlueButton Inc. and by respective authors (see below).
 *
 * This program is free software; you can redistribute it and/or modify it under the
 * terms of the GNU Lesser General Public License as published by the Free Software
 * Foundation; either version 3.0 of the License, or (at your option) any later
 * version.
 *
 * BigBlueButton is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along
 * with BigBlueButton; if not, see <http://www.gnu.org/licenses/>.
 */

namespace BigBlueButton;

use BigBlueButton\Core\ApiMethod;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\DeleteRecordingsParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Parameters\PublishRecordingsParameters;
use BigBlueButton\Parameters\UpdateRecordingsParameters;
use BigBlueButton\Responses\ApiVersionResponse;
use BigBlueButton\Responses\CreateMeetingResponse;
use BigBlueButton\Responses\DeleteRecordingsResponse;
use BigBlueButton\Responses\EndMeetingResponse;
use BigBlueButton\Responses\GetDefaultConfigXMLResponse;
use BigBlueButton\Responses\GetMeetingInfoResponse;
use BigBlueButton\Responses\GetMeetingsResponse;
use BigBlueButton\Responses\GetRecordingsResponse;
use BigBlueButton\Responses\IsMeetingRunningResponse;
use BigBlueButton\Responses\JoinMeetingResponse;
use BigBlueButton\Responses\PublishRecordingsResponse;
use BigBlueButton\Responses\SetConfigXMLResponse;
use BigBlueButton\Responses\UpdateRecordingsResponse;
use BigBlueButton\Util\UrlBuilder;
use SimpleXMLElement;

/**
 * Class BigBlueButton
 * @package BigBlueButton
 */
class BigBlueButton
{
    public $securitySalt;
    public $bbbServerBaseUrl;
    public $dummyMode;
    protected $urlBuilder;

    public function __construct($dummyMode = false, $securitySalt = "", $bbbServerBaseUrl = "")
    {
        $this->securitySalt = empty($securitySalt) ? getenv('BBB_SECURITY_SALT') : $securitySalt;
        $this->bbbServerBaseUrl = empty($bbbServerBaseUrl) ? getenv('BBB_SERVER_BASE_URL') : $bbbServerBaseUrl;
        $this->urlBuilder = new UrlBuilder($this->securitySalt, $this->bbbServerBaseUrl);
        $this->dummyMode = $dummyMode;
    }

    /**
     * @return ApiVersionResponse
     *
     * @throws \RuntimeException
     */
    public function getApiVersion()
    {
        $xml = $this->dummyMode ?
            new SimpleXMLElement('<response><returncode>SUCCESS</returncode><version>0.9</version></response>')
            :
            $this->processXmlResponse($this->urlBuilder->buildUrl());

        return new ApiVersionResponse($xml);
    }

    /* __________________ BBB ADMINISTRATION METHODS _________________ */
    /* The methods in the following section support the following categories of the BBB API:
    -- create
    -- getDefaultConfigXML
    -- join
    -- end
    */

    /**
     * @param  CreateMeetingParameters $createMeetingParams
     * @return string
     */
    public function getCreateMeetingUrl($createMeetingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::CREATE, $createMeetingParams->getHTTPQuery());
    }

    /**
     * @param  CreateMeetingParameters $createMeetingParams
     * @return CreateMeetingResponse
     * @throws \RuntimeException
     */
    public function createMeeting($createMeetingParams)
    {
        $xml = $this->dummyMode ?
            new SimpleXMLElement(
                '<response>
    <returncode>SUCCESS1</returncode>
       <message>Teset error</message>
    <messageKey>123</messageKey>
   <meetingID>' . $createMeetingParams->getMeetingId() . '</meetingID>
    <attendeePW>tK6J5cJv3hMLNx5IBePa</attendeePW>
    <moderatorPW>34Heu0uiZYqCZXX9C4m2</moderatorPW>
    <createTime>1453283819419</createTime>
    <voiceBridge>76286</voiceBridge>
    <dialNumber>613-555-1234</dialNumber>
    <createDate>Wed Jan 20 04:56:59 EST 2016</createDate>
    <hasUserJoined>false</hasUserJoined>
    <duration>20</duration>
    <hasBeenForciblyEnded>false</hasBeenForciblyEnded>
    <messageKey>messageKeyContentShouldNeverHappen</messageKey>
    <message>A message that never happens in real world cases.</message>
    </response>'
            )
            :
            $this->processXmlResponse($this->getCreateMeetingUrl($createMeetingParams), $createMeetingParams->getPresentationsAsXML());

        return new CreateMeetingResponse($xml);
    }

    /**
     * @return string
     */
    public function getDefaultConfigXMLUrl()
    {
        return $this->urlBuilder->buildUrl(ApiMethod::GET_DEFAULT_CONFIG_XML);
    }

    /**
     * @return GetDefaultConfigXMLResponse
     * @throws \RuntimeException
     */
    public function getDefaultConfigXML()
    {
        $xml = $this->processXmlResponse($this->getDefaultConfigXMLUrl());

        return new GetDefaultConfigXMLResponse($xml);
    }

    /**
     * @return string
     */
    public function setConfigXMLUrl()
    {
        return $this->urlBuilder->buildUrl(ApiMethod::SET_CONFIG_XML, '', false);
    }

    /**
     * @return SetConfigXMLResponse
     * @throws \RuntimeException
     */
    public function setConfigXML($setConfigXMLParams)
    {
        $setConfigXMLPayload = $this->urlBuilder->buildQs(ApiMethod::SET_CONFIG_XML, $setConfigXMLParams->getHTTPQuery());

        $xml = $this->processXmlResponse($this->setConfigXMLUrl(), $setConfigXMLPayload, 'application/x-www-form-urlencoded');

        return new SetConfigXMLResponse($xml);
    }

    /**
     * @param $joinMeetingParams JoinMeetingParameters
     *
     * @return string
     */
    public function getJoinMeetingURL($joinMeetingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::JOIN, $joinMeetingParams->getHTTPQuery());
    }

    /**
     * @param $joinMeetingParams JoinMeetingParameters
     *
     * @return JoinMeetingResponse
     * @throws \RuntimeException
     */
    public function joinMeeting($joinMeetingParams)
    {

        $xml = $this->dummyMode ?
            new SimpleXMLElement(
                '<response>
    <returncode>SUCCESS</returncode>

    <messageKey>successfullyJoined</messageKey>
    <message>You have joined successfully.</message>
    <meeting_id>fa51ae0c65adef7fe3cf115421da8a6a25855a20-1464618262714</meeting_id>
    <user_id>ao6ehbtvbmhz</user_id>
    <auth_token>huzbpgthac7s</auth_token>
</response>'
            )
            :
            $this->processXmlResponse($this->getJoinMeetingURL($joinMeetingParams));

        return new JoinMeetingResponse($xml);
    }

    /**
     * @param $endParams EndMeetingParameters
     *
     * @return string
     */
    public function getEndMeetingURL($endParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::END, $endParams->getHTTPQuery());
    }

    /**
     * @param $endParams EndMeetingParameters
     *
     * @return EndMeetingResponse
     * @throws \RuntimeException
     * */
    public function endMeeting($endParams)
    {
        $xml = $this->dummyMode ?
            new SimpleXMLElement(
                '<response>
    <returncode>SUCCESS</returncode>
  <messageKey>sentEndMeetingRequest</messageKey>
    <message>A request to end the meeting was sent. Please wait a few seconds, and then use the getMeetingInfo or isMeetingRunning API calls to verify that it was ended.</message>
</response>'
            )
            :
            $this->processXmlResponse($this->getEndMeetingURL($endParams));

        return new EndMeetingResponse($xml);
    }

    /* __________________ BBB MONITORING METHODS _________________ */
    /* The methods in the following section support the following categories of the BBB API:
    -- isMeetingRunning
    -- getMeetings
    -- getMeetingInfo
    */

    /**
     * @param $meetingParams IsMeetingRunningParameters
     * @return string
     */
    public function getIsMeetingRunningUrl($meetingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::IS_MEETING_RUNNING, $meetingParams->getHTTPQuery());
    }

    /**
     * @param $meetingParams
     * @return IsMeetingRunningResponse
     * @throws \RuntimeException
     */
    public function isMeetingRunning($meetingParams)
    {

        $xml = $this->dummyMode ?
            new SimpleXMLElement(
                '<response>
    <returncode>SUCCESS</returncode>
    <running>true</running>
</response>
'
            )
            :
            $this->processXmlResponse($this->getIsMeetingRunningUrl($meetingParams));

        return new IsMeetingRunningResponse($xml);
    }

    /**
     * @return string
     */
    public function getMeetingsUrl()
    {
        return $this->urlBuilder->buildUrl(ApiMethod::GET_MEETINGS);
    }

    /**
     * @return GetMeetingsResponse
     * @throws \RuntimeException
     */
    public function getMeetings()
    {
        $xml = $this->dummyMode ?
            new SimpleXMLElement(
                '<response>
    <returncode>SUCCESS</returncode>
    
    <meetings>
        <meeting>
            <meetingID>Demo Meeting</meetingID>
            <meetingName>Demo Meeting</meetingName>
            <createTime>1453177310703</createTime>
            <createDate>Mon Jan 18 23:21:50 EST 2016</createDate>
            <voiceBridge>76239</voiceBridge>
            <dialNumber>613-555-1234</dialNumber>
            <attendeePW>ap</attendeePW>
            <moderatorPW>mp</moderatorPW>
            <hasBeenForciblyEnded>false</hasBeenForciblyEnded>
            <running>true</running>
            <participantCount>1</participantCount>
            <listenerCount>0</listenerCount>
            <voiceParticipantCount>1</voiceParticipantCount>
            <videoCount>1</videoCount>
            <duration>0</duration>
            <hasUserJoined>true</hasUserJoined>
        </meeting>
        <meeting>
            <meetingID>Test Meeting</meetingID>
            <meetingName>Test Meeting</meetingName>
            <createTime>1453210216834</createTime>
            <createDate>Tue Jan 19 08:30:16 EST 2016</createDate>
            <voiceBridge>16905</voiceBridge>
            <dialNumber>613-555-1234</dialNumber>
            <attendeePW>ap</attendeePW>
            <moderatorPW>mp</moderatorPW>
            <hasBeenForciblyEnded>false</hasBeenForciblyEnded>
            <running>false</running>
            <participantCount>0</participantCount>
            <listenerCount>0</listenerCount>
            <voiceParticipantCount>0</voiceParticipantCount>
            <videoCount>0</videoCount>
            <duration>0</duration>
            <hasUserJoined>true</hasUserJoined>
        </meeting>
        <meeting>
            <meetingID>56e1ae16-3dfc-390d-b0d8-5aa844a25874</meetingID>
            <meetingName>Marty Lueilwitz</meetingName>
            <createTime>1453210075799</createTime>
            <createDate>Tue Jan 19 08:27:55 EST 2016</createDate>
            <voiceBridge>49518</voiceBridge>
            <dialNumber>580.124.3937x93615</dialNumber>
            <attendeePW>f~kxYJeAV~G?Jb+E:ggn</attendeePW>
            <moderatorPW>n:"zWc##Bi.y,d^s,mMF</moderatorPW>
            <hasBeenForciblyEnded>false</hasBeenForciblyEnded>
            <running>true</running>
            <participantCount>5</participantCount>
            <listenerCount>2</listenerCount>
            <voiceParticipantCount>1</voiceParticipantCount>
            <videoCount>3</videoCount>
            <duration>2206</duration>
            <hasUserJoined>true</hasUserJoined>
        </meeting>
    </meetings>
</response>')
            :
            $this->processXmlResponse($this->getMeetingsUrl());

        return new GetMeetingsResponse($xml);
    }

    /**
     * @param $meetingParams GetMeetingInfoParameters
     * @return string
     */
    public function getMeetingInfoUrl($meetingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::GET_MEETING_INFO, $meetingParams->getHTTPQuery());
    }

    /**
     * @param $meetingParams GetMeetingInfoParameters
     * @return GetMeetingInfoResponse
     * @throws \RuntimeException
     */
    public function getMeetingInfo($meetingParams)
    {

        $xml = $this->dummyMode ?
            new SimpleXMLElement(
                '<response>
    <returncode>SUCCESS</returncode>
    <meetingName>Mock meeting for testing getMeetingInfo API method</meetingName>
    <meetingID>117b12ae2656972d330b6bad58878541-28-15</meetingID>
    <internalMeetingID>178757fcedd9449054536162cdfe861ddebc70ba-1453206317376</internalMeetingID>
    <createTime>1453206317376</createTime>
    <createDate>Tue Jan 19 07:25:17 EST 2016</createDate>
    <voiceBridge>70100</voiceBridge>
    <dialNumber>613-555-1234</dialNumber>
    <attendeePW>dbfc7207321527bbb870c82028</attendeePW>
    <moderatorPW>4bfbbeeb4a65cacaefe3676633</moderatorPW>
    <running>true</running>
    <duration>20</duration>
    <hasUserJoined>true</hasUserJoined>
    <recording>true</recording>
    <hasBeenForciblyEnded>false</hasBeenForciblyEnded>
    <startTime>1453206317380</startTime>
    <endTime>1453206325002</endTime>
    <participantCount>2</participantCount>
    <listenerCount>1</listenerCount>
    <voiceParticipantCount>2</voiceParticipantCount>
    <videoCount>1</videoCount>
    <maxUsers>20</maxUsers>
    <moderatorCount>2</moderatorCount>
    <attendees>
        <attendee>
            <userID>amslzbgzzddp</userID>
            <fullName>Ernie Abernathy</fullName>
            <role>MODERATOR</role>
            <isPresenter>true</isPresenter>
            <isListeningOnly>false</isListeningOnly>
            <hasJoinedVoice>true</hasJoinedVoice>
            <hasVideo>true</hasVideo>
            <customdata></customdata>
        </attendee>
        <attendee>
            <userID>xi7y7gpmyq1g</userID>
            <fullName>Barrett Kutch</fullName>
            <role>MODERATOR</role>
            <isPresenter>false</isPresenter>
            <isListeningOnly>false</isListeningOnly>
            <hasJoinedVoice>true</hasJoinedVoice>
            <hasVideo>false</hasVideo>
            <customdata></customdata>
        </attendee>
    </attendees>
    <metadata>
        <bbb-context>Best BBB Developers Club</bbb-context>
        <bn-origin>Moodle</bn-origin>
        <bn-recording-ready-url>
           http://bigbluebutton.org/moodle/mod/bigbluebuttonbn/bbb_broker.php?action=recording_ready
        </bn-recording-ready-url>
        <bbb-origin-tag>moodle-mod_bigbluebuttonbn (2015080609)</bbb-origin-tag>
        <bbb-origin-version>3.0.2 (Build: 20160111)</bbb-origin-version>
        <bbb-origin-server-common-name></bbb-origin-server-common-name>
        <bbb-origin-server-name>bigbluebutton.org</bbb-origin-server-name>
        <bbb-recording-description></bbb-recording-description>
        <bbb-recording-name>Bigbluebutton "ock meeting for testing getMeetingInfo"</bbb-recording-name>
        <bbb-recording-tags></bbb-recording-tags>
    </metadata>
    <messageKey></messageKey>
    <message></message>
</response>')
            :
            $this->processXmlResponse($this->getMeetingInfoUrl($meetingParams));

        return new GetMeetingInfoResponse($xml);
    }

    /* __________________ BBB RECORDING METHODS _________________ */
    /* The methods in the following section support the following categories of the BBB API:
    -- getRecordings
    -- publishRecordings
    -- deleteRecordings
    */

    /**
     * @param $recordingsParams GetRecordingsParameters
     * @return string
     */
    public function getRecordingsUrl($recordingsParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::GET_RECORDINGS, $recordingsParams->getHTTPQuery());
    }

    /**
     * @param $recordingParams
     * @return GetRecordingsResponse
     * @throws \RuntimeException
     */
    public function getRecordings($recordingParams)
    {
        $xml = $this->processXmlResponse($this->getRecordingsUrl($recordingParams));

        return new GetRecordingsResponse($xml);
    }

    /**
     * @param $recordingParams PublishRecordingsParameters
     * @return string
     */
    public function getPublishRecordingsUrl($recordingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::PUBLISH_RECORDINGS, $recordingParams->getHTTPQuery());
    }

    /**
     * @param $recordingParams PublishRecordingsParameters
     * @return PublishRecordingsResponse
     * @throws \RuntimeException
     */
    public function publishRecordings($recordingParams)
    {
        $xml = $this->processXmlResponse($this->getPublishRecordingsUrl($recordingParams));

        return new PublishRecordingsResponse($xml);
    }

    /**
     * @param $recordingParams DeleteRecordingsParameters
     * @return string
     */
    public function getDeleteRecordingsUrl($recordingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::DELETE_RECORDINGS, $recordingParams->getHTTPQuery());
    }

    /**
     * @param $recordingParams DeleteRecordingsParameters
     * @return DeleteRecordingsResponse
     * @throws \RuntimeException
     */
    public function deleteRecordings($recordingParams)
    {
        $xml = $this->processXmlResponse($this->getDeleteRecordingsUrl($recordingParams));

        return new DeleteRecordingsResponse($xml);
    }

    /**
     * @param $recordingParams UpdateRecordingsParameters
     * @return string
     */
    public function getUpdateRecordingsUrl($recordingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::UPDATE_RECORDINGS, $recordingParams->getHTTPQuery());
    }

    /**
     * @param $recordingParams UpdateRecordingsParameters
     * @return UpdateRecordingsResponse
     * @throws \RuntimeException
     */
    public function updateRecordings($recordingParams)
    {
        $xml = $this->processXmlResponse($this->getUpdateRecordingsUrl($recordingParams));

        return new UpdateRecordingsResponse($xml);
    }

    /* ____________________ INTERNAL CLASS METHODS ___________________ */

    /**
     * A private utility method used by other public methods to process XML responses.
     *
     * @param  string $url
     * @param  string $payload
     * @return SimpleXMLElement
     * @throws \RuntimeException
     */
    private function processXmlResponse($url, $payload = '', $contentType = 'application/xml')
    {
        if (extension_loaded('curl')) {
            $ch = curl_init();
            if (!$ch) {
                throw new \RuntimeException('Unhandled curl error: ' . curl_error($ch));
            }
            $timeout = 10;
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            if ($payload != '') {
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-type: ' . $contentType,
                    'Content-length: ' . strlen($payload),
                ]);
            }
            $data = curl_exec($ch);
            if ($data === false) {
                throw new \RuntimeException('Unhandled curl error: ' . curl_error($ch));
            }
            curl_close($ch);

            return new SimpleXMLElement($data);
        }

        if ($payload != '') {
            throw new \RuntimeException('Post XML data set but curl PHP module is not installed or not enabled.');
        }

        try {
            $response = simplexml_load_file($url, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);

            return new SimpleXMLElement($response);
        } catch (\RuntimeException $e) {
            throw new \RuntimeException('Failover curl error: ' . $e->getMessage());
        }
    }
}
