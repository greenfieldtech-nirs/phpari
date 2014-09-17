<?php

/**
 * Created by PhpStorm.
 * User: WildCard
 * Date: 9/6/14
 * Time: 12:08 AM
 */

class sounds //extends phpari
{
    function __construct($connObject = null)
    {
        try {

            if (is_null($connObject)  || is_null($connObject->ariEndpoint))
                throw new Exception("Missing PestObject or empty string", 503);
            $this->pestObject = $connObject->ariEndpoint;
        } catch (Exception $e) {
            die("Exception raised: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine());
        }
    }


    /**
     * GET /sounds
     * List all sounds.
     *
     * @param string $lang   - Lookup sound for a specific language.
     * @param string $format - Lookup sound in a specific format.
     * @return bool
     */
    public function   sounds_list($lang = null ,$format = null)
    {
        try {

            if (is_null($lang))
                throw new Exception("Language  is  not provided or is null", 503);
            if (is_null($format))
                throw new Exception("Language  is  not provided or is null", 503);

            $uri    = "/sounds";
            $getOBJ = array(
                'lang'    => $lang,
                'format'  => $format
            );

            $result = $this->pestObject->get($uri,$getOBJ);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }
    /**
     *
     * GET /sounds/{soundId}
     * Get a sound's details.
     *
     * @param null $soundID
     * @return bool
     */
    public function   sound_detail($soundID = null)
    {
        try {
            if (is_null($soundID))
                throw new Exception("Sound id is  not provided or is null", 503);

            $uri    = "/sounds/".$soundID;
            $result = $this->pestObject->get($uri);
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * PUT /mailboxes/{mailboxName}
     * Change the state of a mailbox. (Note - implicitly creates the mailbox).
     *
     * @param  int - (required) Count of old messages in the mailbox
     * @param  int - (required) Count of new messages in the mailbox
     * @param  null $newMessages
     * @return bool
     */
    public function   mailbox_change_state($mailBoxName = null,$oldMessages = null, $newMessages = null)
    {
        try {

            if (is_null($mailBoxName))
                throw new Exception("Mail box name is  not provided or is null", 503);
            if (is_null($oldMessages))
                throw new Exception("Old messages is  not provided or is null", 503);
            if (is_null($newMessages))
                throw new Exception("New messages is  not provided or is null", 503);

            $uri    = "/mailboxes/".$mailBoxName;

            $putObj = array(
                'oldMessages'=>$oldMessages,
                'newMessages'=>$newMessages

            );
            $result = $this->pestObject->put($uri,$putObj);
            return $result;



        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * DELETE /mailboxes/{mailboxName}
     * Destroy a mailbox.
     */
    public function   mailbox_destroy($mailBoxName = null)
    {
        try {

            if (is_null($mailBoxName))
                throw new Exception("Mail box name is  not provided or is null", 503);

            $uri    = "/mailboxes/".$mailBoxName;
            $result = $this->pestObject->delete($uri);
            return $result;



        } catch (Exception $e) {
            return false;
        }
    }


}