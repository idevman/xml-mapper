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

        $response = [];
        foreach ($rules as $key => $value) {
            $tokens = explode('@', $value);
            $response[$key] = $this->getValue($root, $tokens);
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

}