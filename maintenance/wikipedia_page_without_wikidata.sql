SELECT
    property, entity_id, Cast(value As BINARY), COUNT(*)
FROM
    claims
GROUP BY
    property, Cast(value As BINARY), entity_id
HAVING 
    COUNT(*) > 1