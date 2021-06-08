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
    public function testBasicDataProvider(string $fileName1, string $fileName2, string $expected): void
    {
        $expect = $this->getFixture($expected);
        $expectedContent = file_get_contents($expect);

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
    }
} 