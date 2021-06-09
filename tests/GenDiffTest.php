<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GenDifferTest extends TestCase
{
    public function getFixture(string $fileName): string
    {
        return implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', $fileName]);
    }

    public function testFileFormatException()
    {
        $fFile = $this->getFixture('file1.json');
        $sFile = $this->getFixture('file.doc');
        $this->expectExceptionMessage("Not supported doc Extension");
        genDiff($fFile, $sFile, 'plain');
    }

    /**
     * @dataProvider basicDataProvider
     */
    /*
    public function testBasicDataProvider(string $fileName1, string $fileName2, string $expected): void
    {
        $expect = $this->getFixture($expected);
        $expectedContent = trim(file_get_contents($expect));

        $contentFile1 = $this->getFixture($fileName1);
        $contentFile2 = $this->getFixture($fileName2);

        $difResult = genDiff($contentFile1, $contentFile2);

        $this->assertSame($expectedContent, $difResult);
    }

    public function basicDataProvider(): array
    {
        return [
            'Basic json diff stylish' => ['file1.json', 'file2.json', 'basicDiffJson.txt']
        ];
    } */
        /**
     * @dataProvider diffDataProvider
     */
    public function testDiffData(string $fileName1, string $fileName2, string $format, string $expected) 
    {
        $expect = $this->getFixture($expected);
        $expectedContent = trim(file_get_contents($expect));

        $contentFile1 = $this->getFixture($fileName1);
        $contentFile2 = $this->getFixture($fileName2);

        $difResult = genDiff($contentFile1, $contentFile2, $format);

        $this->assertSame($expectedContent, $difResult);
    }

    public function diffDataProvider(): array 
    {
        return [
           'output plain' => ['filepath1.json', 'filepath2.json', 'plain', 'diffplain.txt'],
           'output stalish' => ['filepath1.yml', 'filepath2.yml', 'stylish', 'diffstalish.txt'],
           'output json' => ['filepath1.json', 'filepath2.json', 'json', 'json.txt']
        ];
    }
} 