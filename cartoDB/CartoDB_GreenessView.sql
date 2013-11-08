SELECT bureaux_region_country.cartodb_id, bureaux_region_country.the_geom_webmercator, COUNT(*) as CommitCount, AVG(transportation.green_weight) as greenFactorAverage
FROM results
INNER JOIN bureaux_region_country
ON
results.office = bureaux_region_country.cartodb_id
INNER JOIN transportation
ON
results.transportation = transportation.cartodb_id
GROUP BY bureaux_region_country.cartodb_id, bureaux_region_country.the_geom_webmercator
