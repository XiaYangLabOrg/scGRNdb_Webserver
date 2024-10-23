# documentation: https://neo4j.com/docs/python-manual/current/
from neo4j import GraphDatabase, DEFAULT_DATABASE
import os
import pathlib
import pandas as pd
import json
import sys

class Neo4jConnection:
    def __init__(self, uri, user, pwd, db=DEFAULT_DATABASE):
        self.__uri = uri
        self.__user = user
        self.__pwd = pwd
        self.driver = None
        self.db = db
        try:
            self.driver = GraphDatabase.driver(self.__uri, auth=(self.__user, self.__pwd))
        except Exception as e:
            print("Failed to create the driver:", e)

    def close(self):
        if self.driver is not None:
            self.driver.close()
    
    # def session_start(self, db=None):
    #     if not self.session:
    #         try:
    #             self.session = self.driver.session(database=db) if db is not None else self.driver.session() 
    #         except Exception as e:
    #             print("Session Start Fail: ", e)
    # def session_close(self):
    #     if not self.session:
    #         self.session.close()



    def query(self, query, parameters=None, db=None):
        '''
        run query
        Inputs:
        - query: str - cypher query input to neo4j
        - parameters: dict - a dict of parameters matched in the query, where keys are the cypher variables, and values are the variable contents.
        - db: str - name of the database being accessed

        Return:
        - tuple of results and query summary stats
        '''
        assert self.driver is not None, "Driver not initialized!"
        session = None
        response = None
        try:
            session = self.driver.session(database=db) if db is not None else self.driver.session(database = self.db) 
            response = session.run(query, parameters)
            res_list = list(response)
            # response = self.driver.execute_query(query, database_=db) if db is not None else self.driver.execute_query(query)
        except Exception as e:
            print("Query failed:", e)
        finally: 
            if session is not None:
                session.close()
        return (res_list, response._summary)
    
    def retrieve_network_names(self):
        '''
        Retrieve all tissue cell type network names
        '''
        query = f"match (n:Gene) where n.tissue is not null return distinct n.tissue as Tissue, n.celltype as `Cell Type`"
        res = self.driver.execute_query(query)
        result_df = pd.DataFrame([r.values(*res.keys) for r in res.records])
        result_df.columns = res.keys
        return(result_df)
    
    def network_gene_query(self, tissue, celltype, genes, nhop, db=None):
        '''
        Query network
        '''
        params = {'Tissue': tissue,
                  'Celltype': celltype,
                  'genes': genes}
        query=f'''MATCH path=(n:Gene)-[r:REGULATES*1..{nhop}]-(n2:Gene) 
WHERE n.name IN $genes AND ALL(rel IN RELATIONSHIPS(path) WHERE rel.tissue=$Tissue and rel.celltype=$Celltype) UNWIND r as rels
RETURN DISTINCT startNode(rels) as HEAD, endNode(rels) as TAIL, rels as REL
'''
        res = self.query(query, params, db=db)

        return res

    def load_network_query(self, network_file, tissue, celltype, head_col="HEAD", tail_col="TAIL", weight_col="WEIGHT", db=None):
        assert network_file.endswith('txt') or network_file.endswith('csv'), "Network file must be txt or csv"
        if network_file.endswith('txt'): 
            sep='\t'
        if network_file.endswith('csv'):
            sep=','
        query = f'''
// Load the CSV file using apoc.load.csv
CALL apoc.load.csv('{network_file}', {{sep: '{sep}', header: true}}) YIELD map AS row
CALL {{
    WITH row
    // Merge the HEAD and TAIL nodes
    MERGE (headNode:Gene {{name: row.{head_col}}})
    MERGE (tailNode:Gene {{name: row.{tail_col}}})
    // Create the relationship with the "WEIGHT" property
    MERGE (headNode)-[r:REGULATES {{tissue: '{tissue}', celltype:'{celltype}', weight:toFloat(row.{weight_col})}}]->(tailNode)
}} IN TRANSACTIONS
'''
        res = self.query(query, db)
        return res

# format neo4j node object for cytoscape.js
def node_format_cyto(node_name):
    return {'data': {'id': node_name}}
# format neo4j node objects as edge for cytoscape.js
def edge_format_cyto(headnode_name, tailnode_name, weight=None):
    res = {'data': {'id': f"{headnode_name}-{tailnode_name}", 'source': headnode_name, 'target': tailnode_name}}
    if weight:
        res['data']['weight'] = weight
    return res
