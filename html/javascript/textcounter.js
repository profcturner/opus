// Dynamic Version by: Nannette Thacker 
// http://www.shiningstar.net 
// Original by :  Ronnie T. Moore 
// Web Site:  The JavaScript Source 
// Use one function for multiple text areas on a page 
// Limit the number of characters per textarea 
// Begin

function textCounter(field, count_field, limit)
{
  if(field.value.length > limit)
    field.value = field.value.substring(0, limit);
  else
    count_field.value = limit - field.value.length;
}
