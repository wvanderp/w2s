<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$wdLink = mysqli_connect("localhost", "root", "password", "esportsdb") or die(mysqli_error($wdLink));
$link = mysqli_connect("localhost", "root", "password", "w2s") or die(mysqli_error($link));

$clearAll = [
    "TRUNCATE `aliases`;",
    "TRUNCATE `claims`;",
    "TRUNCATE `descriptions`;",
    "TRUNCATE `items`;",
    "TRUNCATE `labels`;",
    "TRUNCATE `properties`;",
    "TRUNCATE `qualifiers`;",
    "TRUNCATE `references`;",
];


$getPages = "SELECT page_title, rev_id
              FROM page
              INNER JOIN revision ON revision.rev_id = page.page_latest
              INNER JOIN text ON revision.rev_text_id = text.old_id
              WHERE page_namespace = 120 || page_namespace = 122;
              ";

$getItems = "SELECT wbid, revision_base FROM items";
$getProperties = "SELECT wbid, revision_base FROM properties";

foreach ($clearAll as $clear) {
    $clearAllResult = mysqli_query($link, $clear) or die(mysqli_error($link));
}

$pagesResult = mysqli_query($wdLink, $getPages) or die(mysqli_error($wdLink));

$itemsResult = mysqli_query($link, $getItems) or die(mysqli_error($link));
$propertiesResult = mysqli_query($link, $getProperties) or die(mysqli_error($link));

$pages = array();
$items = array();
$properties = array();

while ($row = mysqli_fetch_row($pagesResult)) {
    $pages[$row[0]] = $row[1];
}

while ($itemRow = mysqli_fetch_row($itemsResult)) {
    $items[$itemRow[0]] = $itemRow[1];
}

while ($propRow = mysqli_fetch_row($propertiesResult)) {
    $properties[$propRow[0]] = $propRow[1];
}

$toDo = array();

foreach ($pages as $entity => $rev) {
    if (strstr($entity, "Q")) {
        if (!array_key_exists($entity, $items)) {
            $toDo[] = $entity;
            continue;
        }

        if ($rev != $items[$entity]) {
            $toDo[] = $entity;
        }
    }

    if (strstr($entity, "P")) {
        if (!array_key_exists($entity, $properties)) {
            $toDo[] = $entity;
            continue;
        }

        if ($rev != $properties[$entity]) {
            $toDo[] = $entity;
        }
    }
}

if (sizeof($toDo) == 0) {
    die("nothing to do");
}

$labelsValues = "";
$descriptionsValues = "";
$aliasesValues = "";
$claimsValues = "";
$itemsValues = "";
$propertiesValues = "";
$qualifiersValues = "";
$referencesValues = "";

foreach ($toDo as $entity) {
    $entitySql = "SELECT old_text, rev_id
              FROM page
              INNER JOIN revision ON revision.rev_id = page.page_latest
              INNER JOIN text ON revision.rev_text_id = text.old_id
              WHERE page_title = '" . $entity . "'";


    $entityResult = mysqli_query($wdLink, $entitySql) or die(mysqli_error($wdLink));
    $resultArray = mysqli_fetch_array($entityResult);

    $entityJson = json_decode($resultArray[0], true);
    $revId = $resultArray[1];


    #labels
    foreach ($entityJson["labels"] as $label) {
        $language = mysqli_real_escape_string($link, $label["language"]);
        $value = mysqli_real_escape_string($link, $label["value"]);
        $labelsValues .= "('" . $entity . "', '" . $language . "', '" . $value . "'),";
    }

    #discription
    foreach ($entityJson["descriptions"] as $description) {
        $language = mysqli_real_escape_string($link, $description["language"]);
        $value = mysqli_real_escape_string($link, $description["value"]);
        $descriptionsValues .= "('" . $entity . "', '" . $language . "', '" . $value . "'),";
    }

    #aliases
    foreach ($entityJson["aliases"] as $lang) {
        foreach ($lang as $aliases) {
            $language = mysqli_real_escape_string($link, $aliases["language"]);
            $value = mysqli_real_escape_string($link, $aliases["value"]);
            $aliasesValues .= "('" . $entity . "', '" . $language . "', '" . $value . "'),";
        }
    }

    #claims
    foreach ($entityJson["claims"] as $prop) {
        foreach ($prop as $claim) {

            if ($claim["mainsnak"]["snaktype"] == "novalue") {
                $wbid = mysqli_real_escape_string($link, $claim["id"]);
                $entity_id = mysqli_real_escape_string($link, $entity);
                $snak_type = mysqli_real_escape_string($link, $claim["mainsnak"]["snaktype"]);
                $property = mysqli_real_escape_string($link, $claim["mainsnak"]["property"]);


                $claimsValues .= "('" . $wbid . "', '" . $entity_id . "', '" . $snak_type . "', '" . $property . "', NULL, NULL),";
                continue;
            }

            if ($claim["mainsnak"]["snaktype"] == "somevalue") {
                $wbid = mysqli_real_escape_string($link, $claim["id"]);
                $entity_id = mysqli_real_escape_string($link, $entity);
                $snak_type = mysqli_real_escape_string($link, $claim["mainsnak"]["snaktype"]);
                $property = mysqli_real_escape_string($link, $claim["mainsnak"]["property"]);


                $claimsValues .= "('" . $wbid . "', '" . $entity_id . "', '" . $snak_type . "', '" . $property . "', NULL, NULL),";

                continue;
            }


            $wbid = mysqli_real_escape_string($link, $claim["id"]);
            $entity_id = mysqli_real_escape_string($link, $entity);
            $snak_type = mysqli_real_escape_string($link, $claim["mainsnak"]["snaktype"]);
            $property = mysqli_real_escape_string($link, $claim["mainsnak"]["property"]);
            $entity_type = mysqli_real_escape_string($link, $claim["mainsnak"]["datavalue"]["type"]);

        switch ($entity_type) {
            case "wikibase-entityid":
                $value = mysqli_real_escape_string($link, $claim["mainsnak"]["datavalue"]["value"]["id"]);
                break;
            case "time":
                $value = mysqli_real_escape_string($link, json_encode($claim["mainsnak"]["datavalue"]["value"]));
                break;
            default:
                $value = mysqli_real_escape_string($link, $claim["mainsnak"]["datavalue"]["value"]) or var_dump($claim["mainsnak"]["datavalue"]["value"], $entity_type);;
        }



            $claimsValues .= "('" . $wbid . "', '" . $entity_id . "', '" . $snak_type . "', '" . $property . "', '" . $entity_type . "', '" . $value . "'),";

        }
    }

    #items
    if (strstr($entity, "Q")) {
        $itemsValues .= "('" . $entity . "', '" . $revId . "'),";
    }

    #propertyies
    if (strstr($entity, "P")) {
        $propertiesValues .= "('" . $entity . "', '" . $revId . "', '" . $entityJson["datatype"] . "'),";
    }

    #qualifieres
    foreach ($entityJson["claims"] as $prop) {
        foreach ($prop as $claim) {
            if (isset($claim["qualifiers"])) {
                $claimId = $claim["id"];
                foreach ($claim["qualifiers"] as $prop) {
                    foreach ($prop as $claim) {

                        if ($claim["snaktype"] == "novalue" || $claim["snaktype"] == "somevalue") {
                            continue;
                        }

                        $wbid = mysqli_real_escape_string($link, $claim["hash"]);
                        $entity_id = mysqli_real_escape_string($link, $claimId);
                        $snak_type = mysqli_real_escape_string($link, $claim["snaktype"]);
                        $property = mysqli_real_escape_string($link, $claim["property"]);
                        $entity_type = mysqli_real_escape_string($link, $claim["datavalue"]["type"]);
                        $value = mysqli_real_escape_string($link, json_encode($claim["datavalue"]["value"]));

                        $qualifiersValues .= "('" . $wbid . "', '" . $entity_id . "', '" . $snak_type . "', '" . $property . "', '" . $entity_type . "', '" . $value . "'),";

                    }
                }

            }
        }
    }

    #reference
    foreach ($entityJson["claims"] as $prop) {
        foreach ($prop as $claim) {
            if (isset($claim["references"])) {
                $claimId = $claim["id"];
                foreach ($claim["references"] as $ref) {

                    $refId = $ref["hash"];
                    foreach ($ref["snaks"] as $reference) {
                        $reference = $reference[0];
                        if ($reference["snaktype"] == "novalue" || $reference["snaktype"] == "somevalue") {
                            continue;
                        }

                        $wbid = mysqli_real_escape_string($link, $refId);
                        $entity_id = mysqli_real_escape_string($link, $claimId);
                        $snak_type = mysqli_real_escape_string($link, $reference["snaktype"]);
                        $property = mysqli_real_escape_string($link, $reference["property"]);
                        $entity_type = mysqli_real_escape_string($link, $reference["datavalue"]["type"]);
                        $value = mysqli_real_escape_string($link, json_encode($reference["datavalue"]["value"]));

                        $referencesValues .= "('" . $wbid . "', '" . $entity_id . "', '" . $snak_type . "', '" . $property . "', '" . $entity_type . "', '" . $value . "'),";

                    }
                }
            }
        }
    }

}

$labelsValues = substr($labelsValues, 0, -1);
$descriptionsValues = substr($descriptionsValues, 0, -1);
$aliasesValues = substr($aliasesValues, 0, -1);
$claimsValues = substr($claimsValues, 0, -1);
$itemsValues = substr($itemsValues, 0, -1);
$propertiesValues = substr($propertiesValues, 0, -1);
$qualifiersValues = substr($qualifiersValues, 0, -1);
$referencesValues = substr($referencesValues, 0, -1);


$labelsSql = "INSERT INTO `w2s`.`labels` (`entity_id`, `language`, `text`) VALUES " . $labelsValues . ";";
$descriptionsSql = "INSERT INTO `w2s`.`descriptions` (`entity_id`, `language`, `text`) VALUES " . $descriptionsValues . ";";
$aliasesSql = "INSERT INTO `w2s`.`aliases` (`entity_id`, `language`, `text`) VALUES " . $aliasesValues . ";";
$claimsSql = "INSERT INTO `w2s`.`claims` (`wbid`, `entity_id`, `snaktype`, `property`, `entity_type`, `value`) VALUES " . $claimsValues . ";";
$itemsSql = "INSERT INTO `w2s`.`items` (`wbid`, `revision_base`) VALUES " . $itemsValues . ";";
$propertiesSql = "INSERT INTO `w2s`.`properties` (`wbid`, `revision_base`, `entity_type`) VALUES " . $propertiesValues . ";";
$qualifiersSql = "INSERT INTO `w2s`.`qualifiers` (`wbid`, `claim_id`, `snaktype`, `property`, `entity_type`, `value`) VALUES " . $qualifiersValues . ";";
$referencesSql = "INSERT INTO `w2s`.`references` (`wbid`, `claim_id`, `snaktype`, `property`, `entity_type`, `value`) VALUES " . $referencesValues . ";";

mysqli_query($link, $labelsSql) or die("1 " . mysqli_error($link));
mysqli_query($link, $descriptionsSql) or die("2 " . mysqli_error($link));
mysqli_query($link, $aliasesSql) or die("3 " . mysqli_error($link));
mysqli_query($link, $claimsSql) or die("4 " . mysqli_error($link));
mysqli_query($link, $itemsSql) or die("5 " . mysqli_error($link));
mysqli_query($link, $propertiesSql) or die("6 " . mysqli_error($link));
mysqli_query($link, $qualifiersSql) or die("7 " . mysqli_error($link));
mysqli_query($link, $referencesSql) or die("8 " . mysqli_error($link));


mysqli_close($wdLink);
mysqli_close($link);

echo "done";