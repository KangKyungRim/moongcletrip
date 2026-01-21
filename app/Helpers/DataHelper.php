<?php

    // decode
    function safe_json_decode($json, $default = [], $assoc = true)
        {
            $decoded = json_decode($json, $assoc);

            if (json_last_error() !== JSON_ERROR_NONE || is_string($decoded)) {
                $decoded = json_decode($decoded, $assoc);
            }

            return (json_last_error() === JSON_ERROR_NONE && (is_array($decoded) || is_object($decoded)))
                ? $decoded
                : $default;
        }


    // 성급 걸러내기
    function extract_stay_rating($tagString) {
            if (empty($tagString)) return '';
    
            $validRatings = ['1성', '2성', '3성', '4성', '5성'];
            $tags = explode(':-:', $tagString);
    
            foreach ($tags as $tag) {
                if (in_array($tag, $validRatings, true)) {
                    return $tag;
                }
            }
    
            return '';
        }