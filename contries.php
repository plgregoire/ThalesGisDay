


http://thalesgisday.cartodb.com/api/v2/sql?format=GeoJSON&q=SELECT%20tm_world_borders_simpl_0_3.name,%20tm_world_borders_simpl_0_3.the_geom%20FROM%20tm_world_borders_simpl_0_3%20inner%20join%20bureaux_thales%20on%20st_contains(tm_world_borders_simpl_0_3.the_geom,%20bureaux_thales.the_geom)
