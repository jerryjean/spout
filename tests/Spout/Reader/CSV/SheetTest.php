<?php

namespace Box\Spout\Reader\CSV;

use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\TestUsingResource;

/**
 * Class SheetTest
 *
 * @package Box\Spout\Reader\CSV
 */
class SheetTest extends \PHPUnit_Framework_TestCase
{
    use TestUsingResource;

    /**
     * @return void
     */
    public function testReaderShouldReturnCorrectSheetInfos()
    {
        $sheet = $this->openFileAndReturnSheet('csv_standard.csv');

        $this->assertEquals('', $sheet->getName());
        $this->assertEquals(0, $sheet->getIndex());
        $this->assertTrue($sheet->isActive());
    }

    /**
     * @param string $fileName
     * @return Sheet
     */
    private function openFileAndReturnSheet($fileName)
    {
        $resourcePath = $this->getResourcePath($fileName);
        $reader = ReaderFactory::create(Type::CSV);
        $reader->open($resourcePath);

        $sheet = $reader->getSheetIterator()->current();

        //$reader->close();

        return $sheet;
    }

    public function testRowIteratorCount()
    {
        /** @var Sheet[] $sheets */
        $sheets= [
            $this->openFileAndReturnSheet('row_counts_0.csv'),
            $this->openFileAndReturnSheet('row_counts_1.csv'),
            $this->openFileAndReturnSheet('row_counts_1000.csv'),
            ];

        $counts =[0,1,1000];

        foreach ($sheets as $key=>$sheet)
        {
            $rowIterator = $sheet->getRowIterator();

            $this->assertEquals($counts[$key],count($rowIterator));
        }
    }
}
