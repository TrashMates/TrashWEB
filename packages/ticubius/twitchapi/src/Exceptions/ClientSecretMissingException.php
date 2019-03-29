<?php
/**
 * Created by IntelliJ IDEA.
 * User: ticubius
 * Date: 24/02/19
 * Time: 22:57
 */

namespace TiCubius\TwitchAPI\Exceptions;


use Throwable;

class ClientSecretMissingException extends \Exception
{
    /**
     * ClientSecretMissingException constructor.
     *
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(int $code = 0, Throwable $previous = null)
    {
        $message = "TWITCH_CLIENT_SECRET is required in your environment file";

        parent::__construct($message, $code, $previous);
    }

}
