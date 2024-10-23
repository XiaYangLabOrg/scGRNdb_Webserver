#!/opt/miniconda3/bin/python

import argparse
import os


if __name__ == '__main__':
    parser = argparse.ArgumentParser(description="Prepare SCING config file")
    parser.add_argument('--step', choices=['scing','module_pathway'], type=str, help="preparing config for which step", required=True)
    # general arguments
    parser.add_argument('--main_branch_path', type=str, help="path to scNetworkAtlas repo", default="./")
    parser.add_argument('--base_dir', type=str, help="path to scRNAseq data directory", default="./data/")
    parser.add_argument('--adata_dir', type=str, help="path to adata directory", default="tissue_adata")
    parser.add_argument('--celltype_column', type=str, help="cell type column in the single cell object")
    # cell mapping args
    parser.add_argument('--cell_mapping_mapping_file', type=str, help="tab-separated file with columns for Original Cell Type and Broader Cell Type")
    # supercell args
    parser.add_argument('--supercell_dir', type=str, help="output path to store supercells", default="supercells/")
    parser.add_argument('--filetype', type=str, help="file type for counts data", default="h5ad")
    parser.add_argument('--tissue_celltype_file', type=str, help="name of txt file to store all existing adata paths", default="tissue_celltype_file.txt")
    # build grn args
    parser.add_argument('--num_networks', type=int, help="number of intermediate networks", default=100)
    parser.add_argument('--supercell_file', type=str, help="name of txt file to store all existing supercell file paths", default="supercells.txt")
    parser.add_argument('--intermediate_dir', type=str, help="output for intermediate networks", default="saved_networks/intermediate_networks")
    parser.add_argument('--build_ncore', type=int, help="number of cores used to build each network", default=1)
    parser.add_argument('--build_mem_per_core', type=int, help="memory per core in GB for build", default=16)
    # merge grn args
    parser.add_argument('--consensus', type=float, nargs="+", help="list of consensus thresholds to test", default=[0.5])
    parser.add_argument('--final_outdir', type=str, help="output for final networks", default="saved_networks/final_networks")
    parser.add_argument('--merge_ncore', type=int, help="number of cores used for merge network", default=12)
    parser.add_argument('--merge_mem_per_core', type=int, help="memory per core in GB for merge", default=4)
    # module detection args
    parser.add_argument('--module_network_dir', type=str, help="network directory", default="saved_networks/final_networks")
    parser.add_argument('--module_outdir', type=str, help="module output directory", default="gene_memberships")
    parser.add_argument('--min_module_size', type=int, help="minumum module size", default=10)
    parser.add_argument('--max_module_size', type=int, help="maximum module size", default=300)

    # pathway enrichment args
    parser.add_argument('--enrichment_pathway_file', type=str, help="pathway enrichment file path", default="/u/project/xyang123/shared/reference/pathways/human/GO_BP_Hs.txt")
    parser.add_argument('--enrichment_pathway_db', type=str, help="pathway database name", default="GO_BP")
    parser.add_argument('--enrichment_outdir', type=str, help="pathway enrichment output directory", default="enrichment")

    # save file arguments    
    parser.add_argument('--config_outfile', type=str, help="name of config file", default="config.py")
    parser.add_argument('--run_pipeline_commands', type=str, help="name of pipeline commands file", default="run_commands.sh")

    args = parser.parse_args()

    # make config file
    config_file = args.config_outfile
    os.makedirs(os.path.dirname(config_file), exist_ok=True)
    with open(config_file, "w") as f:
        f.write("# User-configurable inputs\n# -----------------------------------\n\n# Configuration settings (these settings are read into submission scripts)\n")
        
        # check if all inputs exist for each step
        f.write(f"#cloned scGRNdb repo\n\
main_branch_path = \"{args.main_branch_path}\"\n\
base_dir = \"{args.base_dir}\"\n\n")
        
        if args.step == 'scing':
            script_folder_pref = "server_"
        else:
            script_folder_pref = ""
        f.write(
f"scing_config = {{\n\
    'copy_cmds':\n\
        [f'cp -r {args.main_branch_path}/{script_folder_pref}shell_scripts/* temp/shell_scripts/',\n\
        f'cp -r {args.main_branch_path}/{script_folder_pref}submission_scripts/* temp/submission_scripts/',\n\
        f'cp -r {args.main_branch_path}/{script_folder_pref}python_files/* temp/python_files/'],\n\n"
        )
        
        # cell mapping and supercells
        if args.cell_mapping_mapping_file:
            f.write(
    f"\t'cell_mapping': {{\n\
        'base_dir': '{args.base_dir}',\n\
        'mapping_file': '{args.cell_mapping_mapping_file}',\n\
        'adata_dir': '{args.adata_dir}',\n\
        'celltype_column:'{args.celltype_column}'\n\
    }},\n\
    'pseudobulking': {{\n\
        'tissue_dir': 'tissue_adata',\n\
        'supercell_dir': '{args.supercell_dir}',\n\
        'filetype': 'h5ad', # npz or h5ad\n\
        'celltype_col': 'celltypes',\n\
        'tissue_celltype_file': '{args.tissue_celltype_file}',\n\
    }},\n")
        else:
            f.write(
    f"\t'pseudobulking': {{\n\
        'tissue_dir': '{args.adata_dir}',\n\
        'supercell_dir': '{args.supercell_dir}',\n\
        'filetype': 'h5ad', # npz or h5ad\n\
        'celltype_col': '{args.celltype_column}',\n\
        'tissue_celltype_file': '{args.tissue_celltype_file}',\n\
    }},\n")
        
        # build grn args
        f.write(
    f"\t'build_grn': {{\n\
        'num_networks': {args.num_networks},\n\
        'supercell_dir': '{args.supercell_dir}',\n\
        'supercell_file': '{args.supercell_file}',\n\
        'out_dir': '{args.intermediate_dir}',\n\
        'ncore': {args.build_ncore},\n\
        'mem_per_core': {args.build_mem_per_core},\n\
    }},\n")
        
        # merge grn args
        f.write(
    f"\t'merge_networks': {{\n\
        'supercell_dir': '{args.supercell_dir}',\n\
        'supercell_file': '{args.supercell_file}',\n\
        'intermediate_dir': '{args.intermediate_dir}',\n\
        'consensus': {args.consensus}, # must be list\n\
        'out_dir': '{args.final_outdir}',\n\
        'ncore': {args.merge_ncore},\n\
        'mem_per_core': {args.merge_mem_per_core},\n\
    }},\n")
        
        # module detection
        f.write(
    f"\t'gene_membership': {{\n\
        'network_dir': '{args.module_network_dir}',\n\
        'network_file': 'final_networks',\n\
        'network_ext': 'txt',\n\
        'out_dir': 'gene_memberships',\n\
        'min_module_size': {args.min_module_size},\n\
        'max_module_size': {args.max_module_size},\n\
        'submit_command': 'bash',\n\
    }},\n")

        # pathway enrichment
        f.write(
    f"\t'enrichment': {{\n\
        'module_dir': 'gene_memberships',\n\
        'module_file': 'gene_membership_files',\n\
        'module_name_col': 'module',\n\
        'module_gene_col': 'genes',\n\
        'pathway_file': '{args.enrichment_pathway_file}',\n\
        'pathway_db': '{args.enrichment_pathway_db}',\n\
        'pathway_name_col': 'MODULE',\n\
        'pathway_gene_col': 'GENE',\n\
        'min_overlap': 10,\n\
        'pathway_size_min': 10,\n\
        'pathway_size_max': 500,\n\
        'out_dir': '{args.enrichment_outdir}',\n\
        'submit_command': 'bash',\n\
    }},\n")

        f.write(
f"}}\n\
# -----------------------------------\n\
# End of User-configurable inputs ")
        
    print(f"{config_file} created")
    
    commands_file = args.run_pipeline_commands
    os.makedirs(os.path.dirname(commands_file), exist_ok=True)

    with open(commands_file, "w") as f:
        f.write(f"cp {args.main_branch_path}/run_pipeline.py ./\n")
        # run scing commands
        if args.step == 'scing':
            f.write('module load anaconda3\n')
            f.write('conda activate scing\n')
            f.write("python run_pipeline.py setup --y\n")
            if args.cell_mapping_mapping_file:
                f.write("python run_pipeline.py cell_mapping --y\n")
            f.write("python run_pipeline.py pseudobulking --y\n")
            f.write("python run_pipeline.py build_grn --y\n")
            f.write("python run_pipeline.py merge_networks --y\n")
        else:
            python_cmd="/opt/miniconda3/envs/networks/bin/python"
            f.write(
f'''source /opt/miniconda3/etc/profile.d/conda.sh
conda activate networks
{python_cmd} run_pipeline.py setup --y
''')
            # create module status running file
            f.write(
f'''touch module_is_running
if [ -f "temp/python_files/ModuleBasedDimensionalityReduction.py" ]            
then
    {python_cmd} run_pipeline.py gene_membership --y
else
    echo "module scripts do not exist"
fi
touch module_is_done
''')
            # f.write(f"{python_cmd} run_pipeline.py gene_membership --y\n")
            f.write(
f'''touch pathway_is_running
if [ -f "gene_memberships/network.gene_membership.txt" ]         
then
    {python_cmd} run_pipeline.py enrichment --y
else
    echo "module output does not exist"
fi
touch pathway_is_done
''')
    print(f"{commands_file} created")
