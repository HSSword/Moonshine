<?php

switch ($a){
	case 'RefreshLocales':
		refreshLocales();
		usleep(250000); //Sleep here to ensure db is ready
		$return = standardMessage('Locales refreshed', false);
		$return['js'] = 'executePatch(\'getTable\', {tableName: \'translation\', replace: \'#translation-factory-table\'}); stopLoading();';
		break;
}

function refreshLocales () : void {
	$calls = [];

	scanEntity(str_removeFromEnd(_ROOT_PATH, '/'), $calls);

	foreach ($calls as $call){
		foreach (explode(',', getConfig("SUPPORTED_LOCALES")) as $LOCALE){
			$existingTranslation = executeQuery(
				_BASE_DB_HOOK,
				'SELECT 1 FROM translation WHERE original = ? AND locale = ?;',
				[['s' => $call], ['s' => $LOCALE]]
			);
			if($existingTranslation instanceof mysqli_result){
				if($existingTranslation->fetch_row() == null){
					executeQuery(
						_BASE_DB_HOOK,
						'INSERT INTO translation (original, translated, locale) VALUES (?, ?, ?);',
						[
							['s' => $call],
							['s' => $call],
							['s' => $LOCALE]
						]
					);
				}
			}
		}
	}
	//Delete unused locales
	$oldTranslation = executeQuery(
		_BASE_DB_HOOK,
		'SELECT id_translation, original FROM translation;'
	);
	if($oldTranslation instanceof mysqli_result){
		while(($reader = $oldTranslation->fetch_row()) != null){
			if(!in_array($reader[1], $calls)){
				executeQuery(
					_BASE_DB_HOOK,
					'DELETE FROM translation WHERE id_translation = ?',
					[['i' => $reader[0]]]
				);
			}
		}
	}
}
function scanEntity (string $path, array &$calls){
	foreach (glob($path.'/*') as $file){
		if($file == __FILE__) continue;
		if(is_dir($file)){
			scanEntity($file, $calls);
		}else{
			$fileInfo = pathinfo($file);
			if($fileInfo['extension'] == 'php'){
				$content = cat($file);
				if($content){
					$matches = [];
					preg_match_all("/(function )?(translate)( )?(\()('|\")([a-zA-Z0-9 _\-]+)('|\")(\))/", $content, $matches);
					if(!empty($matches[0])){
						$matchs = $matches[0];
						foreach ($matchs as $match){
							if(str_startsWith($match, 'function ')) continue;
							$match = str_replace('"', '\'', $match);
							$match = str_removeFromStart($match, 'translate(\'');
							$match = str_removeFromEnd($match, '\')');
							if(!in_array($match, $calls)) {
								$calls[] = $match;
							}
						}
					}
				}
			}
		}
	}
}