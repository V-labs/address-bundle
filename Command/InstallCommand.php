<?php

namespace Vlabs\AddressBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Vlabs\AddressBundle\Entity\City;
use Vlabs\AddressBundle\Entity\Country;
use Vlabs\AddressBundle\Entity\Department;
use Vlabs\AddressBundle\Entity\Region;
use Symfony\Component\Console\Input\InputOption;

class InstallCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('vlabs:address:install')
            ->addOption('limit', null, InputOption::VALUE_OPTIONAL)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__.'/../Resources/data');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {

            $path = $file->getRealpath();
            $filename = $file->getRelativePathname();

            $country = new Country();
            $country->setAbbreviation(substr($filename, 0, 2));
            $country->setName(substr($filename, 3, -4));
            $this->getContainer()->get('doctrine.orm.entity_manager')->persist($country);

            $data = $this->csvToArray($path);

            $regions = $departments = [];
            $csvRegions = $csvDepartments = [];

            $i=1;
            foreach ($data as $row) {

                if($input->getOption('limit') && $i == intval($input->getOption('limit'))){
                    break;
                }

                $csvRegion = $row[3];
                $csvDepartment = $row[5];
                $csvDepartmentCode = $row[6];
                $csvCity = $row[2];
                $csvCityZipCode = $row[1];
                $csvCityLatitude = $row[9];
                $csvCityLongitude = $row[10];

                if (!in_array($csvRegion, $csvRegions) && !empty($csvRegion)) {
                    $region = new Region();
                    $region->setName($csvRegion);
                    $region->setCountry($country);
                    $this->getContainer()->get('doctrine.orm.entity_manager')->persist($region);
                    $regions[] = $region;
                    $csvRegions[] = $csvRegion;
                }

                if (!in_array($csvDepartment, $csvDepartments) && !empty($csvDepartment)) {
                    $department = new Department();
                    $department->setName($csvDepartment);
                    $department->setCode($csvDepartmentCode);
                    $department->setRegion($regions[array_search($csvRegion, $csvRegions)]);
                    $this->getContainer()->get('doctrine.orm.entity_manager')->persist($department);

                    $departments[] = $department;
                    $csvDepartments[] = $csvDepartment;
                }

                if ($this->isCityImportAllowed($csvCityZipCode) && !empty($csvCityZipCode)) {
                    $city = new City();
                    $city->setName($csvCity);
                    $city->setZipCode(sprintf("%05d", $csvCityZipCode));
                    $city->setLatitude($csvCityLatitude);
                    $city->setLongitude($csvCityLongitude);
                    $city->setDepartment($departments[array_search($csvDepartment, $csvDepartments)]);
                    $this->getContainer()->get('doctrine.orm.entity_manager')->persist($city);
                }
                $i++;
            }

        }
        $this->getContainer()->get('doctrine.orm.entity_manager')->flush();
    }

    /**
     * @param $cityZipCode
     * @return bool
     */
    private function isCityImportAllowed($cityZipCode)
    {
        if(strpos($cityZipCode, 'CEDEX') !== false){
            return false;
        }

        return true;
    }

    /**
     * @param $path
     * @return array
     */
    private function csvToArray($path)
    {
        $data = [];

        if (($handle = fopen($path, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ';')) !== false) {
                $data[] = $row;
            }
            fclose($handle);
        }
        return $data;
    }
}
