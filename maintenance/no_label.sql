SELECT
  wbid,
  text
FROM items
  LEFT JOIN labels ON items.wbid = labels.entity_id
WHERE text IS NULL