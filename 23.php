<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

function getPartsFromFullname ($fullName)
{
    
    return array_combine(['surname', 'name', 'patronomyc'], explode(' ', $fullName));
}

function getFullnameFromParts($surname, $name, $patronomyc)
{
    return $surname.' '.$name.' '.$patronomyc;
}

function getShortName($fullName)
{
    $splittedNameArray = getPartsFromFullname ($fullName);
    
    return $splittedNameArray['name'].' '.mb_substr ($splittedNameArray['surname'],0,1).'.';
}

function getGenderFromName($fullName)
{
    $nameParts = getPartsFromFullname($fullName);
    
    return (
       (mb_substr($nameParts['patronomyc'], mb_strlen($nameParts['patronomyc'])-3) =='вна' ? -1 : 0) +
       (mb_substr($nameParts['patronomyc'], mb_strlen($nameParts['patronomyc'])-3) =='вич' ? 1 : 0) +
       (mb_substr($nameParts['name'], mb_strlen($nameParts['name'])-1) =='а' ? -1 : 0) +
       (mb_substr($nameParts['name'], mb_strlen($nameParts['name'])-1) =='й' ? 1 : 0) +
       (mb_substr($nameParts['name'], mb_strlen($nameParts['name'])-1) =='н' ? 1 : 0) +
       (mb_substr($nameParts['surname'], mb_strlen($nameParts['surname'])-2) =='ва' ? -1 : 0) +
       (mb_substr($nameParts['surname'], mb_strlen($nameParts['surname'])-1) =='в' ? 1 : 0) 
    ) <=> 0;
    
    
}

function getGenderDescription ($namesArray)
{
    $resultArr = [ 1 => 0.0, -1 => 0.0, 0 => 0.0];
    $genders = [-1 => 'Женщины', 0 => 'Не удалось определить', 1 => 'Мужчины'];
    $result = "Гендерный состав аудитории:\n---------------------------\n";
    $cnt = count($namesArray);
    if ($cnt > 0) 
    {    
        foreach($namesArray as $key => $value)
        {
            $resultArr[getGenderFromName($value['fullname'])]++;
        }
        
        foreach($resultArr as $key => &$value)
        {
            $result.= $genders[$key].' - '.round($value/$cnt*100, 1)."%\n";
        }
    }
    else
    {
      $cnt.='Аудитория отсутствует'  ;
    }
    return $result;
}

function array_swap(&$array,$swap_a,$swap_b){
    list($array[$swap_a],$array[$swap_b]) = array($array[$swap_b],$array[$swap_a]);
}

function getPerfectPartner ($surname, $name, $patronomyc, $namesArray)
{
    $name =  mb_convert_case($name, MB_CASE_TITLE_SIMPLE);
    $surname =  mb_convert_case($surname, MB_CASE_TITLE_SIMPLE);
    $patronomyc =  mb_convert_case($patronomyc, MB_CASE_TITLE_SIMPLE);
    
    $fullName = getFullnameFromParts($surname, $name, $patronomyc);
    $gender = getGenderFromName($fullName);
    while(count($namesArray)>0)
    {
        $randomPerson = array_rand($namesArray, 1);
        $randomGender = getGenderFromName($namesArray[$randomPerson]['fullname']);
        if ($randomGender == -$gender)
        {
            $randomPercent = rand(5000, 10000) / 100;
            return getShortName($fullName).' + '.getShortName($namesArray[$randomPerson]['fullname'])." = \n♡ Идеально на $randomPercent%";
        }
        else
        {
           unset($namesArray[$randomPerson]) ;
        }
        
    }
}

var_dump(getPartsFromFullname($example_persons_array[0]['fullname']) );

echo getFullnameFromParts('Иванов','Иван','Петрович'), "\n";

echo getShortName(getFullnameFromParts('Иванов','Иван','Петрович')),"\n";

echo getGenderFromName('Иванов Иван Иванович');

echo getGenderDescription($example_persons_array);

echo getPerfectPartner('Иванова','аННа','Петровна', $example_persons_array);

?>;