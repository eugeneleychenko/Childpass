<?php

class DbCommand extends CConsoleCommand
{
    public function actionTest()
    {
        echo 'Test';
        exit();
    }

    public function actionFixture($all=true, $tables='')
    {
        $fm=Yii::app()->fixture;
        $fm->checkIntegrity(false);

        //read list of tables from fixture dictories
        if($all && !$tables)
        {
            echo 'Loading fixtures for all tables...'."\n";


            $handle=opendir($fm->basePath);
            while($file=readdir($handle))
            {
                if($file=='.' || $file=='..')
                    continue;
                $tables.=mb_substr($file, 0, -4).',';
            }
            closedir($handle);

            $tables=mb_substr($tables, 0, -1);
        }
        else
            echo 'Loading fixtures for specified tables...'."\n";


        $connection = $fm->getDbConnection();

        //load data to tables
        $tableNames=explode(',', $tables);

        foreach($tableNames as $tableName)
        {
            $connection->createCommand("SET IDENTITY_INSERT [$tableName] ON")->execute();
            echo $tableName."\n";

            $fm->resetTable($tableName);

            $fm->loadFixture($tableName);
            $connection->createCommand("SET IDENTITY_INSERT [$tableName] OFF")->execute();

        }

        $fm->checkIntegrity(true);
        echo 'Done.';


    }
}