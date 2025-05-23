import { escapeAttribute } from "@wordpress/escape-html";
const el = wp.element.createElement;
const icons = {};
icons.sptabfreeIcon = el('img', {src: escapeAttribute( sp_tab_free_gb_block.url + 'admin/GutenbergBlock/assets/wp-tabs-icon.svg' )});
export default icons;