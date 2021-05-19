<?php


namespace App\Libraries\Exercises\FrameAnalysis\Handlers;


class FrameHandlerManager
{
    private static $handlers;

    public static function add(FrameHandler $frameHandler) {
        FrameHandlerManager::$handlers[] = $frameHandler;
    }

    public static function find(int $frameType) {
        foreach (FrameHandlerManager::$handlers as $handler) {
            if ($handler->getFrameType() === $frameType) {
                return $handler;
            }
        }
        return null;
    }
}