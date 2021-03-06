<?php
/*---------------------------------------------\
|											   |
| @Author:       Andrey Brykin (Drunya)        |
| @Email:        drunyacoder@gmail.com         |
| @Site:         http://atomx.net              |
| @Version:      1.0                           |
| @Project:      CMS                           |
| @Package       AtomX CMS                     |
| @Subpackege    Escape filter                 |
| @Copyright     ©Andrey Brykin 2010-2014      |
| @Last mod      2014/03/01                    |
|----------------------------------------------|
|											   |
| any partial or not partial extension         |
| CMS Fapos,without the consent of the         |
| author, is illegal                           |
|----------------------------------------------|
| Любое распространение                        |
| CMS Fapos или ее частей,                     |
| без согласия автора, является не законным    |
\---------------------------------------------*/

class Fps_Viewer_Filter_Escape {


	public function compile($value, Fps_Viewer_CompileParser $compiler)
	{
        if (!is_callable($value)) throw new Exception('(Filter_Escape):Value for filtering must be callable.');

        $compiler->raw('htmlspecialchars(');
        $value($compiler);
        $compiler->raw(')');
	}
	
	
	public function __toString()
	{
		$out = '[filter]:escape' . "\n";
		return $out;
	}
}