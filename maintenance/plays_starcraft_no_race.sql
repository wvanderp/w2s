SELECT  t1.entity_id
FROM
    (SELECT wbid as entity_id FROM items) t1
LEFT JOIN
    (SELECT entity_id FROM claims WHERE property = 'P2') t2 
ON t1.entity_id = t2.entity_id
WHERE t2.entity_id IS NULL
