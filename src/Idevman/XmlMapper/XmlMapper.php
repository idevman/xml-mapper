<?php

namespace Idevman\XmlMapper;

use SimpleXMLElement;
use Illuminate\Support\Str;

/**
 * Provide functionallity to load informtion from XML
 */
class XmlMapper {

    /**
     * Map xml to specific rules separated by dot.
     * @param rules Rules to handle in format [[path, alias], ...]
     */
    public function mapTo(array $rules, string $xml) {
        $root = new SimpleXMLElement($xml);
        preg_match_all('/xmlns:[\w]+=\"[\w|:|\/|\.]+\"/', $xml, $matches);
        if ($matches && !empty($matches) && !empty($matches[0])) {
            foreach ($matches[0] as $i) {
                $nsTokens = explode('=', str_replace('"', '', substr($i, 6)));
                $root->registerXPathNamespace($nsTokens[0], $nsTokens[1]);
            }
        }

        $response = [];
        foreach ($rules as $key => $value) {
            if (Str::contains($value, '[@') && Str::endsWith($value, ']')) {
                $pathTokens = explode('[@', $value);
                $attributes = explode(',', 
                    str_replace(']', '', str_replace('@', '', $pathTokens[1])));

                $tokens = explode('[', str_replace(']', '', $key));

                $response[$tokens[0]] = $this->getAttributes($root, 
                    $pathTokens[0], $attributes, explode(',', $tokens[1]));
            } else {
                $tokens = explode('@', $value);
                $response[$key] = $this->getValue($root, $tokens);
            }
        }
        return $response;
    }

    /**
     * Retrieve value found path
     * @param root Root node value
     * @param tokens Tokens to filter
     */
    private function getValue(SimpleXMLElement $root, array $tokens) {
        $isArray = false;
        if (Str::endsWith($tokens[0], '[]')) {
            $isArray = true;
            $tokens[0] = str_replace('[]', '', $tokens[0]);
        }
        $nodes = $root->xpath($tokens[0]);
        $isAttribute = count($tokens) === 2;
        if ($isArray) {
            $response = [];
            foreach ($nodes as $i) {
                if ($isAttribute) {
                    array_push($response, (string)$i[$tokens[1]]);
                } else {
                    array_push($response, (string)$i);
                }
            }
            return $response;
        }
        if ($isAttribute) {
            return (string)$nodes[0][$tokens[1]];
        }
        return (string)$nodes[0];
    }

    /**
     * Retrieve value found path
     * @param root Root node value
     * @param path Path nod eto load
     * @param attributes Attributes to load
     * @param labels Arttribute labels data
     */
    private function getAttributes(SimpleXMLElement $root, string $path,
                                   array $attributes, array $labels) {
        $isArray = false;
        if (Str::endsWith($path, '[]')) {
            $isArray = true;
            $path = str_replace('[]', '', $path);
        }
        $nodes = $root->xpath($path);
        $total = count($attributes);
        if ($isArray) {
            $response = [];
            foreach ($nodes as $i) {
                $current = [];
                for ($j = 0; $j < $total; $j++) {
                    $current[$labels[$j]] = (string)$i[$attributes[$j]];
                }
                array_push($response, $current);
            }
            return $response;
        }
        $response = [];
        for ($i = 0; $i < $total; $i++) {
            $response[$labels[$i]] = (string)$nodes[0][$attributes[$i]];
        }
        return $response;
    }

}