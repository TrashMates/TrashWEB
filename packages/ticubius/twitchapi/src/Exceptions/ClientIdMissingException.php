<?php
/**
 * Created by IntelliJ IDEA.
 * User: ticubius
 * Date: 24/02/19
 * Time: 22:57
 */

namespace TiCubius\TwitchAPI\Exceptions;


use Throwable;

class ClientIdMissingException extends \Exception
{
    /**
     * ClientIdMissingException constructor.
     *
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(int $code = 0, Throwable $previous = null)
    {
        $message = "TWITCH_CLIENT_ID is required in your environment file";

        parent::__construct($message, $code, $previous);
    }

}
