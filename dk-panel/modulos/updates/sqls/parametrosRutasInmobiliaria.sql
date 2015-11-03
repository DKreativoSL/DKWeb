ALTER TABLE `sitiosweb`
ADD COLUMN `inmo_rutaPublica`  varchar(150) NULL AFTER `css_tinymce`,
ADD COLUMN `inmo_rutaPrivada`  varchar(150) NULL AFTER `inmo_rutaPublica`;