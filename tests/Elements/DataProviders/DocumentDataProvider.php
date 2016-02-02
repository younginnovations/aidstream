<?php namespace Test\Elements\DataProviders;


trait DocumentDataProvider
{
    protected function getTestInputWithSingleUrl()
    {
        return [
            'http://www.documentTest.com/test.txt' => [
                'filename'   => 'test.txt',
                'url'        => 'http://www.documentTest.com/test.txt',
                'org_id'     => 1,
                'activities' => ['1000' => 'testing']
            ]
        ];
    }

    protected function getTestInputWithMultipleUrl()
    {
        $temp = [];

        for ($i = 0; $i <= 5; $i ++) {
            $temp['http://www.documentTest.com/test' . $i . '.txt'] = [
                'filename'   => 'test' . $i . '.txt',
                'url'        => 'http://www.documentTest.com/test' . $i . '.txt',
                'org_id'     => $i + 1,
                'activities' => [$i . '000' => 'testing' . $i]
            ];
        }

        return $temp;
    }
}
