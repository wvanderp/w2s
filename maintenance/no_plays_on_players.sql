SELECT t1.entity_id
FROM (SELECT entity_id
      FROM claims
      WHERE property = "P2" AND value LIKE "%Q40%") t1 LEFT JOIN (SELECT entity_id
                                                                  FROM claims
                                                                  WHERE property = "P92") t2
    ON t1.entity_id = t2.entity_id
WHERE t2.entity_id IS NULL