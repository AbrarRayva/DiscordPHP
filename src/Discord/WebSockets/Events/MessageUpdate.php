<?php

/*
 * This file is apart of the DiscordPHP project.
 *
 * Copyright (c) 2016 David Cole <david@team-reflex.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the LICENSE.md file.
 */

namespace Discord\WebSockets\Events;

use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;

class MessageUpdate extends Event
{
    /**
     * Returns the formatted data.
     *
     * @param array   $data
     * @param Discord $discord
     *
     * @return Message
     */
    public function getData($data, $discord)
    {
        return new Message((array) $data, true);
    }

    /**
     * Updates the Discord instance with the new data.
     *
     * @param mixed   $data
     * @param Discord $discord
     *
     * @return Discord
     */
    public function updateDiscordInstance($data, $discord)
    {
        foreach ($discord->guilds as $index => $guild) {
            foreach ($guild->channels as $cindex => $channel) {
                if ($channel->id == $data->channel_id) {
                    foreach ($channel->messages as $mindex => $message) {
                        if ($message->id == $data->id) {
                            $channel->messages->pull($mindex);

                            if (! isset($data->content)) {
                                $channel->messages->push(new Message(array_merge((array) $message, (array) $data), true));
                            } else {
                                $channel->messages->push($data);
                            }

                            break;
                        }
                    }

                    $guild->channels->pull($cindex);
                    $guild->channels->push($channel);

                    break;
                }
            }

            $discord->guilds->pull($index);
            $discord->guilds->push($guild);
        }

        return $discord;
    }
}
