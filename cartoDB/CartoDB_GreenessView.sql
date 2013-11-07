SELECT bureaux_thales.cartodb_id, bureaux_thales.the_geom_webmercator, COUNT(*) as HitCount, AVG(transportation.green_weight) as weightAverage
FROM results
INNER JOIN bureaux_thales
ON
results.office = bureaux_thales.cartodb_id
INNER JOIN transportation
ON
results.transportation = transportation.cartodb_id
GROUP BY bureaux_thales.cartodb_id, bureaux_thales.the_geom_webmercator
