<?php

use tthe\TagScheme\TaggingEntity;
use tthe\TagScheme\Util\DateUtil;

describe('Tag', function() {
    $m = new TaggingEntity('example.org', DateUtil::FIRST_OF_YEAR);

    test('toString', function () use ($m) {
        $t = $m->mint('test')->toString();
        expect($t)->toBe('tag:example.org,2023:test');
    });

    test('string type cast', function () use ($m) {
        $t = (string) $m->mint('test');
        expect($t)->toBe('tag:example.org,2023:test');
    });

    test('json_encode', function () use ($m) {
        $t = json_encode($m->mint('test'));
        expect($t)->toBe('"tag:example.org,2023:test"');
    });

    test('empty resource', function () use ($m) {
        $t = $m->mint('')->toString();
        expect($t)->toBe('tag:example.org,2023:');
    });

    test('special characters are percent encoded', function () use ($m) {
        $t = $m->mint('test ?#é')->toString();
        expect($t)->toBe('tag:example.org,2023:test%20%3F%23%C3%A9');
    });

    test('with fragment', function () use ($m) {
        $t = $m->mint('test')->withFragment('frag')->toString();
        expect($t)->toBe('tag:example.org,2023:test#frag');
    });

    test('with query', function () use ($m) {
        $t = $m->mint('test')->withQuery(['k' => 'v'])->toString();
        expect($t)->toBe('tag:example.org,2023:test?k=v');
    });

    test('with fragment and query, encoded', function () use ($m) {
        $t = $m->mint('test')
            ->withQuery(['k' => ' v'])
            ->withFragment('fr ag')
            ->toString();
        expect($t)->toBe('tag:example.org,2023:test?k=%20v#fr%20ag');
    });

    test('object is cloned', function () use ($m) {
        $t = $m->mint('test');
        $t2 = $t->withFragment('frag');

        expect($t)->not()->toBe($t2);
        expect($t->getFragment())->toBeNull();
        expect($t2->getFragment())->toBeInstanceOf(\tthe\TagScheme\Components\Fragment::class);
    });
});