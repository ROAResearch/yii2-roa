<?php

namespace roaresearch\yii2\roa\test;

/**
 * Interface to test ROA resources services.
 *
 * @author Angel (Faryshta) Guevara <angeldelcaos@gmail.com>
 */
interface Tester
{
    public const HAL_JSON_CONTENT_TYPE = 'application/hal+json; charset=UTF-8';

    public const HAL_XML_CONTENT_TYPE = 'application/hal+xml; charset=UTF-8';

    /**
     * Saves a token identified by an unique name.
     *
     * @param string $tokenName unique name to identify the tokens.
     * @param string $token oauth2 authorization token
     */
    public function storeToken(string $tokenName, string $token);

    /**
     * Authenticates a user stored in `$tokens`
     *
     * @param string $tokenName
     */
    public function amAuthByToken(string $tokenName);

    /**
     * Checks over the HTTP pagination headers and (optionally) its values.
     */
    public function seePaginationHttpHeaders();

    /**
     * Checks over the HTTP content type header value.
     */
    public function seeContentTypeHttpHeader(
        string $contentType = self::HAL_CONTENT_TYPE
    );
}
