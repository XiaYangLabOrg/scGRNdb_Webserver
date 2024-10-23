import neo4j_utils as n4u
import argparse
import json




parser = argparse.ArgumentParser(description="Making network queries")
parser.add_argument('--tissue', type=str, help="tissue name in neo4j database", required=True)
parser.add_argument('--celltype', type=str, help="celltype name in neo4j database", required=True)
# parser.add_argument('--genes', nargs='+', type=str, help="list of genes to query", required=True)
parser.add_argument('--genes', type=str, help="list of genes to query", required=True)
parser.add_argument('--existing_node_ids', type=str, help="list of existing nodes in network viewer", default=None)
parser.add_argument('--existing_edge_ids', type=str, help="list of existing egdes in network viewer", default=None)
parser.add_argument('--nhops', type=int, help='depth from input genes to search for neighbors', default=1)
parser.add_argument('--db_user', type=str, help="neo4j db user name", required=True)
parser.add_argument('--db_pwd', type=str, help="neo4j db password", required=True)
parser.add_argument('--db_uri', type=str, help="neo4j db uri", default="bolt://localhost:7687")
parser.add_argument('--db_name', type=str, help="neo4j db name", default="neo4j")
args = parser.parse_args()

tissue = args.tissue
celltype = args.celltype
# genes = args.genes
# print(args.genes)
genes = json.loads(args.genes)['genes']
# print(genes)
existing_nodes = json.loads(args.existing_node_ids)['existingNodes'] if args.existing_node_ids else []
existing_edges = json.loads(args.existing_edge_ids)['existingEdges'] if args.existing_edge_ids else []

nhops = args.nhops
db_user = args.db_user
db_pwd = args.db_pwd
db_useruri = args.db_uri
db = args.db_name

# tissue = 'Blood'
# celltype = 'macrophage'
# genes = ['APOE','MT-RNR2'] # as list
# db = 'neo4j'

# establish db connection
conn = n4u.Neo4jConnection(uri=args.db_uri, user=args.db_user, pwd=args.db_pwd)
# conn = n4u.Neo4jConnection(uri="bolt://localhost:7687", user="neo4j", pwd="scgrndb_yanglab")
# run query
res, summary = conn.network_gene_query(tissue=tissue, celltype=celltype, genes=genes, nhop=nhops, db=db)

# loop through records
cy_elements = [] # list of cytoscape node and edge elements

for record in res:
    head = n4u.node_format_cyto(record['HEAD']['name'])
    if head['data']['id'] not in existing_nodes:
        existing_nodes.append(head['data']['id'])
        cy_elements.append(head)

    tail = n4u.node_format_cyto(record['TAIL']['name'])
    if tail['data']['id'] not in existing_nodes:
        existing_nodes.append(tail['data']['id'])
        cy_elements.append(tail)

    edge = n4u.edge_format_cyto(record['HEAD']['name'],record['TAIL']['name'],record['REL']['weight'])
    if edge['data']['id'] not in existing_edges:
        existing_edges.append(edge['data']['id'])
    
    if head not in cy_elements:
        cy_elements.append(head)
    if tail not in cy_elements:
        cy_elements.append(tail)
    cy_elements.append(edge)

# print out json of cytoscape elements
out_dict = {
    'main_output': {'elements': cy_elements}, 
    'run_time':f"{summary.result_available_after/1000:.3f}"
    }
if args.existing_node_ids:
    out_dict['existing_nodes'] = existing_nodes
if args.existing_edge_ids:
    out_dict['existing_edges'] = existing_edges

print(json.dumps(out_dict))