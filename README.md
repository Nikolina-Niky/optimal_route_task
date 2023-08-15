# How to run
PHP framework used: Symfony

## Run the service
run server with instruction
```bash
symfony server:start -d
```
use REST endpoint 
```bash
routing/{source}/{destination}
```
### examples 
A route between Czechia and Italy: `/routing/CZE/ITA` results in 

"CZE","AUT","ITA"

A route between Lesotho and Malaysia: `/routing/LSO/MYS` results in 

"LSO","ZAF","BWA","ZMB","COD","CAF","SDN","EGY","ISR","JOR","IRQ","IRN","AFG","CHN","MMR","THA","MYS"


A route between Croatia and Slovenia: `/routing/HRV/SVN` results in

"HRV","SVN"

A Route only for Croatia: `/routing/HRV/HRV` results in "HRF"


