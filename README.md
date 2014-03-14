nxc_grid
========
Installation

1. Copy nxc_grid extension to extensions directory
2. Acivate it
3. Regenerate autoloads array
4. NXC Grid datatype is available and ready to use

Using

1. In backend choose grid width and add cell(s) 
2. Add resourse to the cell(s)
3. Template name for the cell is formed by rule: "cell_" + {class name} + {dimension by X} + "x" + {dimension by Y}.
If there are no templates with this name, script will use template with default name (cell_default).
