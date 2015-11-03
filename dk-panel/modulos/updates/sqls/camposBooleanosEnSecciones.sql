ALTER TABLE `secciones`
ADD COLUMN `ch_CampoTitulo`  tinyint(1) NULL AFTER `estado`,
ADD COLUMN `ch_CampoSubTitulo`  tinyint(1) NULL AFTER `ch_CampoTitulo`,
ADD COLUMN `ch_CampoCuerpo`  tinyint(1) NULL AFTER `ch_CampoSubTitulo`,
ADD COLUMN `ch_CampoCuerpoAvance`  tinyint(1) NULL AFTER `ch_CampoCuerpo`,
ADD COLUMN `ch_CampoFechaPublicacion`  tinyint(1) NULL AFTER `ch_CampoCuerpoAvance`,
ADD COLUMN `ch_CampoImagen`  tinyint(1) NULL AFTER `ch_CampoFechaPublicacion`,
ADD COLUMN `ch_CampoArchivo`  tinyint(1) NULL AFTER `ch_CampoImagen`,
ADD COLUMN `ch_CampoURL`  tinyint(1) NULL AFTER `ch_CampoArchivo`,
ADD COLUMN `ch_CampoCampoExtra`  tinyint(1) NULL AFTER `ch_CampoURL`;